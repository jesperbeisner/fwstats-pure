<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Functional\Importer;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Interface\DatabaseInterface;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Interface\PlayerStatusServiceInterface;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\Doubles\FreewarDumpServiceDummy;
use Jesperbeisner\Fwstats\Tests\Doubles\PlayerStatusServiceDummy;

/**
 * @covers \Jesperbeisner\Fwstats\Importer\PlayerImporter
 */
final class PlayerImporterTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();
    }

    public function test_it_will_import_players_dump_successfully(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::BANNED));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::assertCount(0, $database->select("SELECT * FROM players"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        self::assertCount(3, $database->select("SELECT * FROM players"));
    }

    public function test_it_will_update_daily_xp_changes_successfully(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::BANNED));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::assertCount(0, $database->select("SELECT * FROM players"));
        self::assertCount(0, $database->select("SELECT * FROM players_xp_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        self::assertCount(3, $database->select("SELECT * FROM players"));
        self::assertCount(3, $database->select("SELECT * FROM players_xp_history"));
        self::assertSame([
            ['id' => 1, 'world' => 'afsrv', 'player_id' => 1, 'start_xp' => 100, 'end_xp' => 100, 'day' => (new DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d H:i:s')],
            ['id' => 2, 'world' => 'afsrv', 'player_id' => 2, 'start_xp' => 200, 'end_xp' => 200, 'day' => (new DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d H:i:s')],
            ['id' => 3, 'world' => 'afsrv', 'player_id' => 3, 'start_xp' => 300, 'end_xp' => 300, 'day' => (new DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d H:i:s')],
        ], $database->select("SELECT * FROM players_xp_history"));

        // We need to set up the container once again so the PlayerImporter gets the new FreewarDumpService
        self::setUpContainer();

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 500, 0, 500, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 600, 0, 600, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 700, 0, 700, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        self::assertSame([
            ['id' => 1, 'world' => 'afsrv', 'player_id' => 1, 'start_xp' => 100, 'end_xp' => 500, 'day' => (new DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d H:i:s')],
            ['id' => 2, 'world' => 'afsrv', 'player_id' => 2, 'start_xp' => 200, 'end_xp' => 600, 'day' => (new DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d H:i:s')],
            ['id' => 3, 'world' => 'afsrv', 'player_id' => 3, 'start_xp' => 300, 'end_xp' => 700, 'day' => (new DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d H:i:s')],
        ], $database->select("SELECT * FROM players_xp_history"));
    }

    public function test_it_will_create_a_new_player_created_entry_when_dump_has_a_new_player(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::BANNED));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
        ]);

        self::assertCount(2, $database->select("SELECT * FROM players"));
        self::assertCount(0, $database->select("SELECT * FROM players_created_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        self::assertCount(3, $database->select("SELECT * FROM players"));
        self::assertCount(1, $database->select("SELECT * FROM players_created_history"));
    }

    public function test_it_will_create_player_name_change_history(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::BANNED));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'name-change', 'Keuroner', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_name_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_name_history");
        self::assertCount(1, $histories);
        self::assertIsArray($histories[0]);
        self::assertSame(3, $histories[0]['player_id']);
        self::assertSame('Test-3', $histories[0]['old_name']);
        self::assertSame('name-change', $histories[0]['new_name']);
    }
}
