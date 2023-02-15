<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Interface\ImporterInterface;
use Jesperbeisner\Fwstats\Interface\PlayerStatusServiceInterface;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerClanHistory;
use Jesperbeisner\Fwstats\Model\PlayerCreatedHistory;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;
use Jesperbeisner\Fwstats\Model\PlayerProfessionHistory;
use Jesperbeisner\Fwstats\Model\PlayerRaceHistory;
use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;
use Jesperbeisner\Fwstats\Model\PlayerXpHistory;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerClanHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerXpHistoryRepository;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Integration\Importer\PlayerImporterTest
 */
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
        private PlayerStatusServiceInterface $playerStatusService,
        private PlayerXpHistoryRepository $playerXpHistoryRepository,
        private PlayerCreatedHistoryRepository $playerCreatedHistoryRepository,
    ) {
    }

    public function import(WorldEnum $world): void
    {
        $clans = $this->clanRepository->findAllByWorld($world);
        $players = $this->playerRepository->findAllByWorld($world);
        $playersDump = $this->freewarDumpService->getPlayersDump($world);

        if (count($players) === 0) {
            $this->trackDailyXpChanges($playersDump);
            $this->playerRepository->insertPlayers($world, $playersDump);
        }

        foreach ($players as $player) {
            if (isset($playersDump[$player->playerId])) {
                $playerDump = $playersDump[$player->playerId];

                $this->checkName($player, $playerDump);
                $this->checkRace($player, $playerDump);
                $this->checkClan($player, $playerDump, $clans);
                $this->checkProfession($player, $playerDump);
            } else {
                // player_id not available in dump anymore: player was probably deleted or banned
                if (PlayerStatusEnum::UNKNOWN !== $playerStatus = $this->playerStatusService->getStatus($player)) {
                    $createdAndUpdated = new DateTimeImmutable();
                    $this->playerStatusHistoryRepository->insert(new PlayerStatusHistory(null, $world, $player->playerId, $player->name, $playerStatus, $createdAndUpdated, null, $createdAndUpdated));
                }
            }
        }

        foreach ($playersDump as $playerDump) {
            // Player is in dump but not in database: Player created or unbanned/restored
            if (!isset($players[$playerDump->playerId])) {
                // When $playerStatusHistory is null it's a new player, otherwise update the PlayerStatusHistory
                if (null === $playerStatusHistory = $this->playerStatusHistoryRepository->findByPlayer($playerDump)) {
                    $this->playerCreatedHistoryRepository->insert(new PlayerCreatedHistory(null, $world, $playerDump->playerId, $playerDump->name, new DateTimeImmutable()));
                } else {
                    $this->playerStatusHistoryRepository->update($playerStatusHistory);
                }
            }
        }

        $this->trackDailyXpChanges($playersDump);
        $this->playerRepository->insertPlayers($world, $playersDump);
    }

    /**
     * @param array<Player> $playersDump
     */
    private function trackDailyXpChanges(array $playersDump): void
    {
        foreach ($playersDump as $playerDump) {
            $this->playerXpHistoryRepository->create(new PlayerXpHistory(null, $playerDump->world, $playerDump->playerId, $playerDump->totalXp, $playerDump->totalXp, new DateTimeImmutable('today')));
        }
    }

    private function checkName(Player $player, Player $playerDump): void
    {
        if ($player->name !== $playerDump->name) {
            $this->playerNameHistoryRepository->insert(new PlayerNameHistory(null, $player->world, $player->playerId, $player->name, $playerDump->name, new DateTimeImmutable()));
        }
    }

    private function checkRace(Player $player, Player $playerDump): void
    {
        if ($player->race !== $playerDump->race) {
            $this->playerRaceHistoryRepository->insert(new PlayerRaceHistory(null, $player->world, $player->playerId, $player->race, $playerDump->race, new DateTimeImmutable()));
        }
    }

    /**
     * @param Clan[] $clans
     */
    private function checkClan(Player $player, Player $playerDump, array $clans): void
    {
        if ($player->clanId !== $playerDump->clanId) {
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

            $this->playerClanHistoryRepository->insert(new PlayerClanHistory(null, $player->world, $player->playerId, $oldClanId, $newClanId, $oldShortcut, $newShortcut, $oldName, $newName));
        }
    }

    private function checkProfession(Player $player, Player $playerDump): void
    {
        if ($player->profession !== $playerDump->profession) {
            $this->playerProfessionHistoryRepository->insert(new PlayerProfessionHistory(null, $player->world, $player->playerId, $player->profession, $playerDump->profession, new DateTimeImmutable()));
        }
    }
}
