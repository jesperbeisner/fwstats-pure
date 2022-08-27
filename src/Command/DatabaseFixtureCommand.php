<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
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

        $this->writeLine("Creating ranking images...");
        $this->createRankingImages();

        $this->writeLine("Finished the 'app:database-fixture' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }

    private function createPlayers(): void
    {
        $playersFixtureData = require ROOT_DIR . '/data/fixtures/players.php';

        $this->playerRepository->deleteAll();

        /** @var array<string, int|string> $playerData */
        foreach ($playersFixtureData as $playerData) {
            $player = new Player(
                world: WorldEnum::from((string) $playerData['world']),
                playerId: (int) $playerData['playerId'],
                name: (string) $playerData['name'],
                race: (string) $playerData['race'],
                xp: (int) $playerData['xp'],
                soulXp: (int) $playerData['soulXp'],
                totalXp: (int) $playerData['totalXp'],
                clanId: (int) $playerData['clanId'],
                profession: (string) $playerData['profession'],
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

            /** @var array<string, int|string> $clanData */
            foreach ($clansFixtureData as $clanData) {
                $clans[] = new Clan(
                    world: $world,
                    clanId: (int) $clanData['clanId'],
                    shortcut: (string) $clanData['shortcut'],
                    name: (string) $clanData['name'],
                    leaderId: (int) $clanData['leaderId'],
                    coLeaderId: (int) $clanData['coLeaderId'],
                    diplomatId: (int) $clanData['diplomatId'],
                    warPoints: (int) $clanData['warPoints'],
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

        /** @var array{world: WorldEnum, playerId: int, seconds: int, created: string} $playerPlaytimesData */
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

    private function createRankingImages(): void
    {
        foreach (WorldEnum::cases() as $world) {
            $this->rankingImageService->create($world);
        }
    }
}
