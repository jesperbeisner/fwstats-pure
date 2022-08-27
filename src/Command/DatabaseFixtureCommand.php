<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;
use Jesperbeisner\Fwstats\Model\PlayerRaceHistory;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;

final class DatabaseFixtureCommand extends AbstractCommand
{
    public static string $name = 'app:database-fixture';
    public static string $description = 'Loads fixtures for local development into the database.';

    public function __construct(
        private readonly string $appEnv,
        private readonly PlayerRepository $playerRepository,
        private readonly ClanRepository $clanRepository,
        private readonly PlayerActiveSecondRepository $playerActiveSecondRepository,
        private readonly PlayerNameHistoryRepository $playerNameHistoryRepository,
        private readonly PlayerRaceHistoryRepository $playerRaceHistoryRepository,
        private readonly RankingImageService $rankingImageService,
    ) {
    }

    public function execute(): int
    {
        if ($this->appEnv !== 'dev') {
            $this->writeLine("Rhe 'app:database-fixture' command can only be executed in the dev environment.");

            return self::FAILURE;
        }

        $this->startTime();
        $this->writeLine("Starting the 'app:database-fixture' command...");

        $this->writeLine("Creating players...");
        $this->createPlayers();

        $this->writeLine("Creating clans...");
        $this->createClans();

        $this->writeLine("Creating player playtimes...");
        $this->createPlayerPlaytimes();

        $this->writeLine("Creating player name histories...");
        $this->createPlayerNameHistories();

        $this->writeLine("Creating player race histories...");
        $this->createPlayerRaceHistories();

        $this->writeLine("Creating ranking images...");
        $this->createRankingImages();

        $this->writeLine("Finished the 'app:database-fixture' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }

    private function createPlayers(): void
    {
        $playersFixtureData = require ROOT_DIR . '/data/fixtures/players.php';

        $this->playerRepository->deleteAll();

        /** @var array{
         *     world: WorldEnum,
         *     playerId: int,
         *     name: string,
         *     race: string,
         *     xp: int,
         *     soulXp: int,
         *     totalXp: int,
         *     clanId: int,
         *     profession: string
         * } $playerData
         */
        foreach ($playersFixtureData as $playerData) {
            $player = new Player(
                world: $playerData['world'],
                playerId: $playerData['playerId'],
                name: $playerData['name'],
                race: $playerData['race'],
                xp: $playerData['xp'],
                soulXp: $playerData['soulXp'],
                totalXp: $playerData['totalXp'],
                clanId: $playerData['clanId'],
                profession: $playerData['profession'],
                created: new DateTimeImmutable(),
            );

            $this->playerRepository->insert($player);
        }
    }

    private function createClans(): void
    {
        $clansFixtureData = require ROOT_DIR . '/data/fixtures/clans.php';

        $this->clanRepository->deleteAll();

        foreach (WorldEnum::cases() as $world) {
            $clans = [];

            /** @var array{
             *     clanId: int,
             *     shortcut: string,
             *     name: string,
             *     leaderId: int,
             *     coLeaderId: int,
             *     diplomatId: int,
             *     warPoints: int
             * } $clanData
             */
            foreach ($clansFixtureData as $clanData) {
                $clans[] = new Clan(
                    world: $world,
                    clanId: $clanData['clanId'],
                    shortcut: $clanData['shortcut'],
                    name: $clanData['name'],
                    leaderId: $clanData['leaderId'],
                    coLeaderId: $clanData['coLeaderId'],
                    diplomatId: $clanData['diplomatId'],
                    warPoints: $clanData['warPoints'],
                    created: new DateTimeImmutable(),
                );
            }

            $this->clanRepository->insertClans($world, $clans);
        }
    }

    private function createPlayerPlaytimes(): void
    {
        $playerPlaytimesFixtureData = require ROOT_DIR . '/data/fixtures/player-playtimes.php';

        $this->playerActiveSecondRepository->deleteAll();

        /** @var array{
         *     world: WorldEnum,
         *     playerId: int,
         *     seconds: int,
         *     created: string
         * } $playerPlaytimesData
         */
        foreach ($playerPlaytimesFixtureData as $playerPlaytimesData) {
            $playerActiveSecond = new PlayerActiveSecond(
                world: $playerPlaytimesData['world'],
                playerId: $playerPlaytimesData['playerId'],
                seconds: $playerPlaytimesData['seconds'],
                created: new DateTimeImmutable($playerPlaytimesData['created']),
            );

            $this->playerActiveSecondRepository->insert($playerActiveSecond);
        }
    }

    private function createPlayerNameHistories(): void
    {
        $playerNameHistoriesFixtureData = require ROOT_DIR . '/data/fixtures/player-name-histories.php';

        $this->playerNameHistoryRepository->deleteAll();

        /** @var array{
         *     world: WorldEnum,
         *     playerId: int,
         *     oldName: string,
         *     newName: string,
         *     created: DateTimeImmutable
         * } $playerNameHistoryData
         */
        foreach ($playerNameHistoriesFixtureData as $playerNameHistoryData) {
            $playerNameHistory = new PlayerNameHistory(
                world: $playerNameHistoryData['world'],
                playerId: $playerNameHistoryData['playerId'],
                oldName: $playerNameHistoryData['oldName'],
                newName: $playerNameHistoryData['newName'],
                created: $playerNameHistoryData['created'],
            );

            $this->playerNameHistoryRepository->insert($playerNameHistory);
        }
    }

    private function createPlayerRaceHistories(): void
    {
        $playerRaceHistoriesFixtureData = require ROOT_DIR . '/data/fixtures/player-race-histories.php';

        $this->playerRaceHistoryRepository->deleteAll();

        /** @var array{
         *     world: WorldEnum,
         *     playerId: int,
         *     oldRace: string,
         *     newRace: string,
         *     created: DateTimeImmutable
         * } $playerRaceHistoryData
         */
        foreach ($playerRaceHistoriesFixtureData as $playerRaceHistoryData) {
            $playerRaceHistory = new PlayerRaceHistory(
                world: $playerRaceHistoryData['world'],
                playerId: $playerRaceHistoryData['playerId'],
                oldRace: $playerRaceHistoryData['oldRace'],
                newRace: $playerRaceHistoryData['newRace'],
                created: $playerRaceHistoryData['created'],
            );

            $this->playerRaceHistoryRepository->insert($playerRaceHistory);
        }
    }

    private function createRankingImages(): void
    {
        foreach (WorldEnum::cases() as $world) {
            $this->rankingImageService->create($world);
        }
    }
}
