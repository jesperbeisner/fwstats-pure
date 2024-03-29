<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Image\NameChangeImage;
use Jesperbeisner\Fwstats\Image\ProfessionChangeImage;
use Jesperbeisner\Fwstats\Image\RaceChangeImage;
use Jesperbeisner\Fwstats\Image\RankingImage;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;
use Jesperbeisner\Fwstats\Model\PlayerProfessionHistory;
use Jesperbeisner\Fwstats\Model\PlayerRaceHistory;
use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Repository\UserRepository;

final class DatabaseFixtureCommand extends AbstractCommand
{
    public static string $name = 'app:database-fixtures';
    public static string $description = 'Loads fixtures for local development into the database.';

    public function __construct(
        private readonly string $appEnv,
        private readonly string $fixturesDirectory,
        private readonly PlayerRepository $playerRepository,
        private readonly ClanRepository $clanRepository,
        private readonly PlayerActiveSecondRepository $playerActiveSecondRepository,
        private readonly PlayerNameHistoryRepository $playerNameHistoryRepository,
        private readonly PlayerRaceHistoryRepository $playerRaceHistoryRepository,
        private readonly PlayerProfessionHistoryRepository $playerProfessionHistoryRepository,
        private readonly PlayerStatusHistoryRepository $playerStatusHistoryRepository,
        private readonly UserRepository $userRepository,
        private readonly ConfigRepository $configRepository,
        private readonly CreateUserAction $createUserAction,
        private readonly RankingImage $rankingImage,
        private readonly NameChangeImage $nameChangeImage,
        private readonly ProfessionChangeImage $professionChangeImage,
        private readonly RaceChangeImage $raceChangeImage,
    ) {
    }

    public function execute(): int
    {
        if ($this->appEnv !== 'dev') {
            $this->writeLine("The 'app:database-fixtures' command can only be executed in the dev environment.");

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

        $this->writeLine("Creating player profession histories...");
        $this->createPlayerProfessionHistories();

        $this->writeLine("Creating player status histories...");
        $this->createPlayerStatusHistories();

        $this->writeLine("Deleting old config...");
        $this->configRepository->deleteAll();

        $this->writeLine("Creating user account...");
        $this->createUserAccount();

        $this->writeLine("Creating images...");
        $this->createImages();

        $this->writeLine("Finished the 'app:database-fixture' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }

    private function createPlayers(): void
    {
        /** @var array<array{world: WorldEnum, playerId: int, name: string, race: string, xp: int, soulXp: int, totalXp: int, clanId: int, profession: string}> $playersFixtureData */
        $playersFixtureData = require $this->fixturesDirectory . '/players.php';

        $this->playerRepository->deleteAll();

        foreach ($playersFixtureData as $playerData) {
            $player = new Player(
                id: null,
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
        /** @var array<array{clanId: int, shortcut: string, name: string, leaderId: int, coLeaderId: int, diplomatId: int, warPoints: int}> $clansFixtureData */
        $clansFixtureData = require $this->fixturesDirectory . '/clans.php';

        $this->clanRepository->deleteAll();

        foreach (WorldEnum::cases() as $world) {
            $clans = [];

            foreach ($clansFixtureData as $clanData) {
                $clans[] = new Clan(
                    id: null,
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
        /** @var array<array{world: WorldEnum, playerId: int, seconds: int, created: string}> $playerPlaytimesFixtureData */
        $playerPlaytimesFixtureData = require $this->fixturesDirectory . '/player-playtimes.php';

        $this->playerActiveSecondRepository->deleteAll();

        foreach ($playerPlaytimesFixtureData as $playerPlaytimesData) {
            $playerActiveSecond = new PlayerActiveSecond(
                id: null,
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
        /** @var array<array{world: WorldEnum, playerId: int, oldName: string, newName: string, created: DateTimeImmutable}> $playerNameHistoriesFixtureData */
        $playerNameHistoriesFixtureData = require $this->fixturesDirectory . '/player-name-histories.php';

        $this->playerNameHistoryRepository->deleteAll();

        foreach ($playerNameHistoriesFixtureData as $playerNameHistoryData) {
            $playerNameHistory = new PlayerNameHistory(
                id: null,
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
        /** @var array<array{world: WorldEnum, playerId: int, oldRace: string, newRace: string, created: DateTimeImmutable}> $playerRaceHistoriesFixtureData */
        $playerRaceHistoriesFixtureData = require $this->fixturesDirectory . '/player-race-histories.php';

        $this->playerRaceHistoryRepository->deleteAll();

        foreach ($playerRaceHistoriesFixtureData as $playerRaceHistoryData) {
            $playerRaceHistory = new PlayerRaceHistory(
                id: null,
                world: $playerRaceHistoryData['world'],
                playerId: $playerRaceHistoryData['playerId'],
                oldRace: $playerRaceHistoryData['oldRace'],
                newRace: $playerRaceHistoryData['newRace'],
                created: $playerRaceHistoryData['created'],
            );

            $this->playerRaceHistoryRepository->insert($playerRaceHistory);
        }
    }

    private function createPlayerProfessionHistories(): void
    {
        /** @var array<array{world: WorldEnum, playerId: int, oldProfession: string|null, newProfession: string|null, created: DateTimeImmutable}> $playerProfessionHistoriesFixtureData */
        $playerProfessionHistoriesFixtureData = require $this->fixturesDirectory . '/player-profession-histories.php';

        $this->playerProfessionHistoryRepository->deleteAll();

        foreach ($playerProfessionHistoriesFixtureData as $playerProfessionHistoryData) {
            $playerProfessionHistory = new PlayerProfessionHistory(
                id: null,
                world: $playerProfessionHistoryData['world'],
                playerId: $playerProfessionHistoryData['playerId'],
                oldProfession: $playerProfessionHistoryData['oldProfession'],
                newProfession: $playerProfessionHistoryData['newProfession'],
                created: $playerProfessionHistoryData['created'],
            );

            $this->playerProfessionHistoryRepository->insert($playerProfessionHistory);
        }
    }

    private function createPlayerStatusHistories(): void
    {
        /** @var array<array{world: WorldEnum, playerId: int, name: string, status: PlayerStatusEnum, created: DateTimeImmutable, deleted: null|DateTimeImmutable, updated: DateTimeImmutable}> $playerStatusHistoriesFixtureData */
        $playerStatusHistoriesFixtureData = require $this->fixturesDirectory . '/player-status-histories.php';

        $this->playerStatusHistoryRepository->deleteAll();

        foreach ($playerStatusHistoriesFixtureData as $playerStatusHistoryData) {
            $playerProfessionHistory = new PlayerStatusHistory(
                id: null,
                world: $playerStatusHistoryData['world'],
                playerId: $playerStatusHistoryData['playerId'],
                name: $playerStatusHistoryData['name'],
                status: $playerStatusHistoryData['status'],
                created: $playerStatusHistoryData['created'],
                deleted: $playerStatusHistoryData['deleted'],
                updated: $playerStatusHistoryData['updated'],
            );

            $this->playerStatusHistoryRepository->insert($playerProfessionHistory);
        }
    }

    private function createUserAccount(): void
    {
        $this->userRepository->deleteAll();

        $this->createUserAction->configure(['username' => 'admin', 'password' => 'Password12345']);
        $this->createUserAction->run();
    }

    private function createImages(): void
    {
        foreach (WorldEnum::cases() as $world) {
            $this->rankingImage->create($world);
            $this->nameChangeImage->create($world);
            $this->professionChangeImage->create($world);
            $this->raceChangeImage->create($world);
        }
    }
}
