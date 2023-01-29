<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Interface\ImporterInterface;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerClanHistory;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;
use Jesperbeisner\Fwstats\Model\PlayerProfessionHistory;
use Jesperbeisner\Fwstats\Model\PlayerRaceHistory;
use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;
use Jesperbeisner\Fwstats\Model\PlayerXpHistory;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerClanHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerXpHistoryRepository;
use Jesperbeisner\Fwstats\Result\ImportResult;
use Jesperbeisner\Fwstats\Service\PlayerStatusService;

final readonly class PlayerImporter implements ImporterInterface
{
    public function __construct(
        private FreewarDumpServiceInterface $freewarDumpService,
        private ClanRepository $clanRepository,
        private PlayerRepository $playerRepository,
        private PlayerNameHistoryRepository $playerNameHistoryRepository,
        private PlayerRaceHistoryRepository $playerRaceHistoryRepository,
        private PlayerClanHistoryRepository $playerClanHistoryRepository,
        private PlayerProfessionHistoryRepository $playerProfessionHistoryRepository,
        private PlayerStatusHistoryRepository $playerStatusHistoryRepository,
        private PlayerStatusService $playerStatusService,
        private PlayerXpHistoryRepository $playerXpHistoryRepository,
    ) {
    }

    public function import(WorldEnum $world): ImportResult
    {
        $importResult = new ImportResult();
        $importResult->addMessage('Starting PlayerImporter...');

        $clans = $this->clanRepository->findAllByWorld($world);

        $playersDump = $this->freewarDumpService->getPlayersDump($world);
        $players = $this->playerRepository->findAllByWorld($world);

        if (count($players) === 0) {
            $importResult->addMessage("No players found for world '$world->value'. Inserting initial player dump and player xp history into the database.");

            $this->trackDailyXpChanges($playersDump);
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
                // player_id not available in dump anymore: the player was deleted or banned
                // TODO: player_id nicht mehr im Dump vorhanden. Spieler hat sich gelöscht/wurde gebannt?
                // Gelöscht = PlayerName + ba + ID
                // Gebannt = Name noch normal vorhanden
                // Profil parsen und als gebannt/gelöscht einsortieren
                // Bei neuen Spielern gucken, ob diese vorher gelöscht/gebannt waren

                if (PlayerStatusEnum::UNKNOWN !== $playerStatus = $this->playerStatusService->getStatus($world, $player)) {
                    $this->playerStatusHistoryRepository->insert(new PlayerStatusHistory(null, $world, $player->playerId, $player->name, $playerStatus));
                }
            }
        }

        $this->trackDailyXpChanges($playersDump);

        foreach ($playersDump as $playerDump) {
            // Player is in dump but not in database: Player created
            if (!isset($players[$playerDump->playerId])) {
                $importResult->addMessage("Player '$playerDump->name' in world '$world->value' was created.");

                // TODO: Check if player was deleted or banned
                // If yes, it's not a new player. New StatusHistory needs to be created.
                // Otherwise create a new PlayerCreatedHistory
                // $this->playerCreatedHistoryRepository->insert($playerCreatedHistory);
            }
        }

        $this->playerRepository->insertPlayers($world, $playersDump);

        $importResult->addMessage('Finishing PlayerImporter...');

        return $importResult;
    }

    /**
     * @param array<Player> $playersDump
     */
    private function trackDailyXpChanges(array $playersDump): void
    {
        foreach ($playersDump as $playerDump) {
            $this->playerXpHistoryRepository->create(new PlayerXpHistory(null, $playerDump->world, $playerDump->playerId, $playerDump->totalXp, $playerDump->totalXp, (new DateTimeImmutable())->setTime(0, 0, 0)));
        }
    }

    private function checkName(WorldEnum $world, ImportResult $importResult, Player $player, Player $playerDump): void
    {
        if ($player->name !== $playerDump->name) {
            $importResult->addMessage("Player with id '$player->playerId' changed his name in world '$world->value'.");
            $this->playerNameHistoryRepository->insert(new PlayerNameHistory(null, $world, $player->playerId, $player->name, $playerDump->name, new DateTimeImmutable()));
        }
    }

    private function checkRace(WorldEnum $world, ImportResult $importResult, Player $player, Player $playerDump): void
    {
        if ($player->race !== $playerDump->race) {
            $importResult->addMessage("Player with id '$player->playerId' changed his race in world '$world->value'.");
            $this->playerRaceHistoryRepository->insert(new PlayerRaceHistory(null, $world, $player->playerId, $player->race, $playerDump->race, new DateTimeImmutable()));
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

            $this->playerClanHistoryRepository->insert(new PlayerClanHistory(null, $world, $player->playerId, $oldClanId, $newClanId, $oldShortcut, $newShortcut, $oldName, $newName));
        }
    }

    private function checkProfession(WorldEnum $world, ImportResult $importResult, Player $player, Player $playerDump): void
    {
        if ($player->profession !== $playerDump->profession) {
            $importResult->addMessage("Player with id '$player->playerId' changed his profession in world '$world->value'.");
            $this->playerProfessionHistoryRepository->insert(new PlayerProfessionHistory(null, $world, $player->playerId, $player->profession, $playerDump->profession, new DateTimeImmutable()));
        }
    }
}
