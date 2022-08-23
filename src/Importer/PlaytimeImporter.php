<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;

final class PlaytimeImporter implements ImporterInterface
{
    public function __construct(
        private readonly FreewarDumpServiceInterface $freewarDumpService,
        private readonly PlayerActiveSecondRepository $playerActiveSecondRepository,
    ) {
    }

    public function import(WorldEnum $world): ImportResult
    {
        $importResult = new ImportResult();
        $importResult->addMessage('Starting PlaytimeImporter...');

        $players = $this->freewarDumpService->getPlayersDump($world);
        $achievements = $this->freewarDumpService->getAchievementsDump($world);

        $playerActiveSeconds = [];
        foreach ($players as $player) {
            if (isset($achievements[$player->playerId])) {
                $playerAchievements = $achievements[$player->playerId];

                $fieldsWalked = $playerAchievements[1019] ?? 0;
                $fieldsElixir = $playerAchievements[1022] ?? 0;
                $fieldsRun = $playerAchievements[1021] ?? 0;
                $fieldsRunFast = $playerAchievements[1047] ?? 0;

                $seconds = ($fieldsWalked * 4) + ($fieldsElixir * 3) + ($fieldsRun * 2) + $fieldsRunFast;

                $playerActiveSeconds[] = new PlayerActiveSecond(
                    $world,
                    $player->playerId,
                    $seconds,
                    new DateTimeImmutable(),
                );
            }
        }

        $this->playerActiveSecondRepository->insertPlayerActiveSeconds($playerActiveSeconds);

        $importResult->addMessage('Finishing PlaytimeImporter...');

        return $importResult;
    }
}
