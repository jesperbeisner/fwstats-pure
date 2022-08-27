<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Achievement;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\Interface\FreewarDumpServiceInterface;

final class AchievementImporter implements ImporterInterface
{
    public function __construct(
        private readonly FreewarDumpServiceInterface $freewarDumpService,
        private readonly PlayerRepository $playerRepository,
        private readonly AchievementRepository $achievementRepository,
    ) {
    }

    public function import(WorldEnum $world): ImportResult
    {
        $importResult = new ImportResult();
        $importResult->addMessage('Starting AchievementImporter...');

        $achievementsDump = $this->freewarDumpService->getAchievementsDump($world);
        $players = $this->playerRepository->findAllByWorld($world);

        $achievements = [];
        foreach ($players as $player) {
            if (isset($achievementsDump[$player->playerId])) {
                $playerAchievements = $achievementsDump[$player->playerId];

                $achievements[] = new Achievement(
                    world: $world,
                    playerId: $player->playerId,
                    fieldsWalked: $playerAchievements[1019] ?? 0,
                    fieldsElixir: $playerAchievements[1022] ?? 0,
                    fieldsRun: $playerAchievements[1021] ?? 0,
                    fieldsRunFast: $playerAchievements[1047] ?? 0,
                    npcKillsGold: $playerAchievements[1008] ?? 0,
                    normalNpcKilled: $playerAchievements[1000] ?? 0,
                    phaseNpcKilled: $playerAchievements[13] ?? 0,
                    aggressiveNpcKilled: $playerAchievements[31] ?? 0,
                    invasionNpcKilled: $playerAchievements[52] ?? 0,
                    uniqueNpcKilled: $playerAchievements[87] ?? 0,
                    groupNpcKilled: $playerAchievements[79] ?? 0,
                    soulStonesGained: $playerAchievements[18] ?? 0,
                );
            }
        }

        $this->achievementRepository->insertAchievements($world, $achievements);

        $importResult->addMessage('Finishing AchievementImporter...');

        return $importResult;
    }
}
