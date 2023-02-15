<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Interface\ImporterInterface;
use Jesperbeisner\Fwstats\Model\Achievement;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Integration\Importer\AchievementImporterTest
 */
final readonly class AchievementImporter implements ImporterInterface
{
    public function __construct(
        private FreewarDumpServiceInterface $freewarDumpService,
        private PlayerRepository $playerRepository,
        private AchievementRepository $achievementRepository,
    ) {
    }

    public function import(WorldEnum $world): void
    {
        $players = $this->playerRepository->findAllByWorld($world);
        $achievementsDump = $this->freewarDumpService->getAchievementsDump($world);

        $achievements = [];
        foreach ($players as $player) {
            if (isset($achievementsDump[$player->playerId])) {
                $playerAchievements = $achievementsDump[$player->playerId];

                $achievements[] = new Achievement(
                    id: null,
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
                    day: new DateTimeImmutable('today'),
                );
            }
        }

        $this->achievementRepository->insertAchievements($world, $achievements);
    }
}
