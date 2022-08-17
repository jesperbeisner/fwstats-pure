<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\DTO\ClanCreatedHistory;
use Jesperbeisner\Fwstats\DTO\ClanDeletedHistory;
use Jesperbeisner\Fwstats\DTO\ClanNamingHistory;
use Jesperbeisner\Fwstats\DTO\Player;
use Jesperbeisner\Fwstats\DTO\PlayerNameHistory;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNamingHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;

final class ImportWorldStatsCommand extends AbstractCommand
{
    public static string $name = 'app:import-world-stats';
    public static string $description = "Imports the current world stats into the database.";

    public function __construct(
        private readonly FreewarDumpServiceInterface $freewarDumpService,
        private readonly ClanRepository $clanRepository,
        private readonly ClanNamingHistoryRepository $clanNamingHistoryRepository,
        private readonly ClanCreatedHistoryRepository $clanCreatedHistoryRepository,
        private readonly ClanDeletedHistoryRepository $clanDeletedHistoryRepository,
        private readonly PlayerRepository $playerRepository,
        private readonly PlayerNameHistoryRepository $playerNameHistoryRepository,
    ) {
    }

    public function execute(): int
    {
        $start = microtime(true);

        $this->write("Starting the 'app:import-world-stats' command...");

        foreach (WorldEnum::cases() as $world) {
            $this->importClans($world);
            $this->importPlayers($world);
            continue;
            $this->importAchievements($world);
        }

        $end = round((microtime(true) - $start) * 1000);

        $this->write("Finished the 'app:import-world-stats' command in $end ms.");

        return self::SUCCESS;
    }

    private function importClans(WorldEnum $world): void
    {
        $clans = $this->clanRepository->findAllByWorld($world);
        $clansDump = $this->freewarDumpService->getClansDump($world);

        // TODO: Remove
        $this->clanRepository->insertClans($world, $clansDump);
        return;

        if (count($clans) === 0) {
            $this->write("No clans found for world '$world->value'. Only inserting clan dump into the database.");
        } else {
            foreach ($clans as $clan) {
                if (isset($clansDump[$clan->clanId])) {
                    $clanDump = $clansDump[$clan->clanId];

                    if ($clan->shortcut !== $clanDump->shortcut || $clan->name !== $clanDump->name) {
                        $clanNamingHistory = new ClanNamingHistory(
                            world: $clan->world,
                            clanId: $clan->clanId,
                            oldShortcut: $clan->shortcut,
                            newShortcut: $clanDump->shortcut,
                            oldName: $clan->name,
                            newName: $clanDump->name,
                        );

                        $this->clanNamingHistoryRepository->insert($clanNamingHistory);
                    }
                } else {
                    // Clan is in database but not in clan dump: Clan deleted
                    $clanDeletedHistory = new ClanDeletedHistory(
                        world: $clan->world,
                        clanId: $clan->clanId,
                        shortcut: $clan->shortcut,
                        name: $clan->name,
                        leaderId: $clan->leaderId,
                        coLeaderId: $clan->coLeaderId,
                        diplomatId: $clan->diplomatId,
                        warPoints: $clan->warPoints,
                    );

                    $this->clanDeletedHistoryRepository->insert($clanDeletedHistory);
                }
            }

            foreach ($clansDump as $clanDump) {
                // Clan is in dump but not in database: Clan created
                if (!isset($clans[$clanDump->clanId])) {
                    $clanCreatedHistory = new ClanCreatedHistory(
                        world: $clanDump->world,
                        clanId: $clanDump->clanId,
                        shortcut: $clanDump->shortcut,
                        name: $clanDump->name,
                        leaderId: $clanDump->leaderId,
                        coLeaderId: $clanDump->coLeaderId,
                        diplomatId: $clanDump->diplomatId,
                        warPoints: $clanDump->warPoints,
                    );

                    $this->clanCreatedHistoryRepository->insert($clanCreatedHistory);
                }
            }
        }

        $this->clanRepository->insertClans($world, $clansDump);
    }

    private function importPlayers(WorldEnum $world): void
    {
        $playersDump = $this->freewarDumpService->getPlayersDump($world);
        $players = $this->playerRepository->findAllByWorld($world);

        // TODO: Remove
        $this->playerRepository->insertPlayers($world, $playersDump);
        return;

        if (count($players) === 0) {
            $this->write("No players found for world '$world->value'. Inserting player dump into the database.");
        } else {
            foreach ($players as $player) {
                if (isset($playersDumpData[$player->playerId])) {
                    $playerDump = $playersDump[$player->playerId];

                    $this->checkName($world, $player, $playerDump);
                    $this->checkRace($world, $player, $playerDump);
                    $this->checkProfession($world, $player, $playerDump);
                } else {
                    // TODO: player_id nicht mehr im Dump vorhanden. Spieler hat sich gelöscht/wurde gebannt?
                }
            }
        }

        $this->playerRepository->insertPlayers($world, $playersDump);
    }

    private function checkName(WorldEnum $world, Player $player, Player $playerDump): void
    {
        return;
        if ($player->name !== $playerDump->name) {
            $playerNameHistory = new PlayerNameHistory($world, $player->playerId, $player->name, $playerDump->name);
            $this->playerNameHistoryRepository->insert($playerNameHistory);
        }
    }

    private function checkRace(WorldEnum $world, Player $player, Player $playerDump): void
    {

    }

    private function checkProfession(WorldEnum $world, Player $player, Player $playerDump): void
    {

    }
}
