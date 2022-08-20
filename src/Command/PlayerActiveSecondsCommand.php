<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;

final class PlayerActiveSecondsCommand extends AbstractCommand
{
    public static string $name = 'app:player-active-seconds';
    public static string $description = "Imports the current player active seconds into the database.";

    public function __construct(
        private readonly FreewarDumpServiceInterface $freewarDumpService,
        private readonly PlayerActiveSecondRepository $playerActiveSecondRepository,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();

        $this->writeLine("Starting the 'app:player-active-seconds' command...");
        $this->writeLine();

        $playerActiveSeconds = [];
        foreach (WorldEnum::cases() as $world) {
            $players = $this->freewarDumpService->getPlayersDump($world);
            $achievements = $this->freewarDumpService->getAchievementsDump($world);

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
        }

        $this->playerActiveSecondRepository->insertPlayerActiveSeconds($playerActiveSeconds);

        $this->writeLine("Finished the 'app:player-active-seconds' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
