<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use Jesperbeisner\Fwstats\DTO\Clan;
use Jesperbeisner\Fwstats\DTO\Player;
use Jesperbeisner\Fwstats\DTO\PlayerClanHistory;
use Jesperbeisner\Fwstats\DTO\PlayerNameHistory;
use Jesperbeisner\Fwstats\DTO\PlayerProfessionHistory;
use Jesperbeisner\Fwstats\DTO\PlayerRaceHistory;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerClanHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\FreewarDumpService;

final class PlayerImporter implements ImporterInterface
{
    private const PROFILE = 'https://[WORLD].freewar.de/freewar/internal/fight.php?action=watchuser&act_user_id=[PLAYER_ID]';

    public function __construct(
        private readonly FreewarDumpService $freewarDumpService,
        private readonly ClanRepository $clanRepository,
        private readonly PlayerRepository $playerRepository,
        private readonly PlayerNameHistoryRepository $playerNameHistoryRepository,
        private readonly PlayerRaceHistoryRepository $playerRaceHistoryRepository,
        private readonly PlayerClanHistoryRepository $playerClanHistoryRepository,
        private readonly PlayerProfessionHistoryRepository $playerProfessionHistoryRepository,
    ) {
    }

    public function import(WorldEnum $world): ImportResult
    {
        $importResult = new ImportResult();

        $clans = $this->clanRepository->findAllByWorld($world);

        $playersDump = $this->freewarDumpService->getPlayersDump($world);
        $players = $this->playerRepository->findAllByWorld($world);

        if (count($players) === 0) {
            $importResult->addMessage("No players found for world '$world->value'. Inserting player dump into the database.");
            $this->playerRepository->insertPlayers($world, $playersDump);

            return $importResult;
        }

        foreach ($players as $player) {
            if (isset($playersDump[$player->playerId])) {
                $playerDump = $playersDump[$player->playerId];

                $this->checkName($world, $importResult, $player, $playerDump);
                $this->checkRace($world, $importResult, $player, $playerDump);
                $this->checkClan($world, $importResult, $player, $playerDump, $clans);
                $this->checkProfession($world, $importResult, $player, $playerDump);
            } else {
                // TODO: player_id nicht mehr im Dump vorhanden. Spieler hat sich gelöscht/wurde gebannt?
                // Gelöscht = PlayerName + ba + ID
                // Gebannt = Name noch normal vorhanden
                // Profil parsen und als gebannt/gelöscht einsortieren
                // Bei neuen Spielern gucken, ob diese vorher gelöscht/gebannt waren
            }
        }

        foreach ($playersDump as $playerDump) {
            // Player is in dump but not in database: Player created
            if (!isset($players[$playerDump->playerId])) {
                $importResult->addMessage("Player '$playerDump->name' in world '$world->value' was created.");

                // TODO: Do stuff

                // $this->playerCreatedHistoryRepository->insert($playerCreatedHistory);
            }
        }

        $this->playerRepository->insertPlayers($world, $playersDump);

        return $importResult;
    }

    private function checkName(WorldEnum $world, ImportResult $importResult, Player $player, Player $playerDump): void
    {
        if ($player->name !== $playerDump->name) {
            $importResult->addMessage("Player with id '$player->playerId' changed his name in world '$world->value'.");

            $playerNameHistory = new PlayerNameHistory(
                world: $world,
                playerId: $player->playerId,
                oldName: $player->name,
                newName: $playerDump->name
            );

            $this->playerNameHistoryRepository->insert($playerNameHistory);
        }
    }

    private function checkRace(WorldEnum $world, ImportResult $importResult, Player $player, Player $playerDump): void
    {
        if ($player->race !== $playerDump->race) {
            $importResult->addMessage("Player with id '$player->playerId' changed his race in world '$world->value'.");

            $playerRaceHistory = new PlayerRaceHistory(
                world: $world,
                playerId: $player->playerId,
                oldRace: $player->race,
                newRace: $playerDump->race
            );

            $this->playerRaceHistoryRepository->insert($playerRaceHistory);
        }
    }

    /**
     * @param Clan[] $clans
     */
    private function checkClan(WorldEnum $world, ImportResult $importResult, Player $player, Player $playerDump, array $clans): void
    {
        if ($player->clanId !== $playerDump->clanId) {
            $importResult->addMessage("Player with id '$player->playerId' changed his clan in world '$world->value'.");

            if ($player->clanId === null) {
                $oldClanId = null;
                $oldShortcut = null;
                $oldName = null;
            } else {
                if (null === $clan = $clans[$player->clanId] ?? null) {
                    $oldClanId = null;
                    $oldShortcut = null;
                    $oldName = null;
                } else {
                    $oldClanId = $clan->clanId;
                    $oldShortcut = $clan->shortcut;
                    $oldName = $clan->name;
                }
            }

            if ($playerDump->clanId === null) {
                $newClanId = null;
                $newShortcut = null;
                $newName = null;
            } else {
                if (null === $clan = $clans[$playerDump->clanId] ?? null) {
                    $newClanId = null;
                    $newShortcut = null;
                    $newName = null;
                } else {
                    $newClanId = $clan->clanId;
                    $newShortcut = $clan->shortcut;
                    $newName = $clan->name;
                }
            }

            $playerClanHistory = new PlayerClanHistory(
                world: $world,
                playerId: $player->playerId,
                oldClanId: $oldClanId,
                newClanId: $newClanId,
                oldShortcut: $oldShortcut,
                newShortcut: $newShortcut,
                oldName: $oldName,
                newName: $newName,
            );

            $this->playerClanHistoryRepository->insert($playerClanHistory);
        }
    }

    private function checkProfession(WorldEnum $world, ImportResult $importResult, Player $player, Player $playerDump): void
    {
        if ($player->profession !== $playerDump->profession) {
            $importResult->addMessage("Player with id '$player->playerId' changed his profession in world '$world->value'.");

            $playerProfessionHistory = new PlayerProfessionHistory(
                world: $world,
                playerId: $player->playerId,
                oldProfession: $player->profession,
                newProfession: $playerDump->profession
            );

            $this->playerProfessionHistoryRepository->insert($playerProfessionHistory);
        }
    }
}
