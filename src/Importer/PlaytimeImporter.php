<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Interface\ImporterInterface;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;

final readonly class PlaytimeImporter implements ImporterInterface
{
    public function __construct(
        private FreewarDumpServiceInterface $freewarDumpService,
        private PlayerActiveSecondRepository $playerActiveSecondRepository,
    ) {
    }

    public function import(WorldEnum $world): void
    {
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

                $playerActiveSeconds[] = new PlayerActiveSecond(null, $world, $player->playerId, $seconds, new DateTimeImmutable());
            }
        }

        $this->playerActiveSecondRepository->insertPlayerActiveSeconds($playerActiveSeconds);
    }
}
