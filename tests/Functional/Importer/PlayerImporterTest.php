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
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
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

    public function test_it_will_create_player_race_change_history(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_race_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_race_history");

        self::assertCount(1, $histories);

        self::assertIsArray($histories[0]);
        self::assertSame(3, $histories[0]['player_id']);
        self::assertSame('Keuroner', $histories[0]['old_race']);
        self::assertSame('Serum-Geist', $histories[0]['new_race']);
    }

    public function test_it_will_create_player_profession_change_history(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, 'Magieverlängerer', new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, 'Sammler', new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, 'Maschinenbauer', new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, 'Schützer', new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_profession_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_profession_history");

        self::assertCount(3, $histories);

        self::assertIsArray($histories[0]);
        self::assertSame(1, $histories[0]['player_id']);
        self::assertNull($histories[0]['old_profession']);
        self::assertSame('Magieverlängerer', $histories[0]['new_profession']);

        self::assertIsArray($histories[1]);
        self::assertSame(2, $histories[1]['player_id']);
        self::assertSame('Maschinenbauer', $histories[1]['old_profession']);
        self::assertNull($histories[1]['new_profession']);

        self::assertIsArray($histories[2]);
        self::assertSame(3, $histories[2]['player_id']);
        self::assertSame('Schützer', $histories[2]['old_profession']);
        self::assertSame('Sammler', $histories[2]['new_profession']);
    }

    public function test_it_will_create_player_clan_change_history_and_fills_it_with_nulls_when_no_clan_is_available(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, 1, 'Magieverlängerer', new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, 'Sammler', new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, 'Maschinenbauer', new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, 'Schützer', new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_clan_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_clan_history");

        self::assertCount(1, $histories);

        self::assertIsArray($histories[0]);
        self::assertSame(1, $histories[0]['player_id']);
        self::assertNull($histories[0]['old_clan_id']);
        self::assertNull($histories[0]['new_clan_id']);
        self::assertNull($histories[0]['old_shortcut']);
        self::assertNull($histories[0]['new_shortcut']);
        self::assertNull($histories[0]['old_name']);
        self::assertNull($histories[0]['new_name']);
    }

    public function test_it_will_create_player_clan_change_history(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->get(ClanRepository::class)->insertClans(WorldEnum::AFSRV, [
            new Clan(null, WorldEnum::AFSRV, 1, 'test-1', 'Test-1', 100, 0, 0, 0, new DateTimeImmutable()),
            new Clan(null, WorldEnum::AFSRV, 2, 'test-2', 'Test-2', 200, 0, 0, 0, new DateTimeImmutable()),
            new Clan(null, WorldEnum::AFSRV, 3, 'test-3', 'Test-3', 300, 0, 0, 0, new DateTimeImmutable()),
        ]);

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, 1, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, 2, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, 1, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, 3, null, new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_clan_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_clan_history");

        self::assertCount(3, $histories);

        self::assertIsArray($histories[0]);
        self::assertSame(1, $histories[0]['player_id']);
        self::assertNull($histories[0]['old_clan_id']);
        self::assertSame(1, $histories[0]['new_clan_id']);
        self::assertNull($histories[0]['old_shortcut']);
        self::assertSame('test-1', $histories[0]['new_shortcut']);
        self::assertNull($histories[0]['old_name']);
        self::assertSame('Test-1', $histories[0]['new_name']);

        self::assertIsArray($histories[1]);
        self::assertSame(2, $histories[1]['player_id']);
        self::assertSame(1, $histories[1]['old_clan_id']);
        self::assertSame(2, $histories[1]['new_clan_id']);
        self::assertSame('test-1', $histories[1]['old_shortcut']);
        self::assertSame('test-2', $histories[1]['new_shortcut']);
        self::assertSame('Test-1', $histories[1]['old_name']);
        self::assertSame('Test-2', $histories[1]['new_name']);

        self::assertIsArray($histories[2]);
        self::assertSame(3, $histories[2]['player_id']);
        self::assertSame(3, $histories[2]['old_clan_id']);
        self::assertNull($histories[2]['new_clan_id']);
        self::assertSame('test-3', $histories[2]['old_shortcut']);
        self::assertNull($histories[2]['new_shortcut']);
        self::assertSame('Test-3', $histories[2]['old_name']);
        self::assertNull($histories[2]['new_name']);
    }

    public function test_it_will_create_a_new_banned_player_status_history_entry_when_player_is_not_found_in_dump_anymore(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::BANNED));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_status_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_status_history");

        self::assertCount(1, $histories);
        self::assertIsArray($histories[0]);
        self::assertSame(3, $histories[0]['player_id']);
        self::assertSame('Test-3', $histories[0]['name']);
        self::assertSame('banned', $histories[0]['status']);
        self::assertNull($histories[0]['deleted']);
    }

    public function test_it_will_create_a_new_deleted_player_status_history_entry_when_player_is_not_found_in_dump_anymore(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::DELETED));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_status_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_status_history");

        self::assertCount(1, $histories);
        self::assertIsArray($histories[0]);
        self::assertSame(3, $histories[0]['player_id']);
        self::assertSame('Test-3', $histories[0]['name']);
        self::assertSame('deleted', $histories[0]['status']);
        self::assertNull($histories[0]['deleted']);
    }

    public function test_it_will_not_create_a_player_status_history_entry_when_status_is_unknown(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::UNKNOWN));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        self::assertCount(0, $database->select("SELECT * FROM players_status_history"));

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        self::assertCount(0, $database->select("SELECT * FROM players_status_history"));
    }

    public function test_it_when_player_dump_has_a_new_player_which_has_open_status_history_it_will_be_updated(): void
    {
        $database = self::getContainer()->get(DatabaseInterface::class);

        self::getContainer()->get(PlayerStatusHistoryRepository::class)->insert(new PlayerStatusHistory(null, WorldEnum::AFSRV, 4, 'Test-4', PlayerStatusEnum::BANNED, new DateTimeImmutable(), null, new DateTimeImmutable()));

        $freewarDumpService = new FreewarDumpServiceDummy([
            1 => new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            2 => new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            3 => new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, null, new DateTimeImmutable()),
            4 => new Player(null, WorldEnum::AFSRV, 4, 'Test-4', 'Keuroner', 400, 0, 400, null, null, new DateTimeImmutable()),
        ]);

        self::getContainer()->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::getContainer()->get(PlayerRepository::class)->insertPlayers(WorldEnum::AFSRV, [
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Taruner', 200, 0, 200, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Serum-Geist', 300, 0, 300, null, null, new DateTimeImmutable()),
        ]);

        $histories = $database->select("SELECT * FROM players_status_history");

        self::assertCount(1, $histories);
        self::assertIsArray($histories[0]);
        self::assertSame(1, $histories[0]['id']);
        self::assertNull($histories[0]['deleted']);

        self::getContainer()->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        $histories = $database->select("SELECT * FROM players_status_history");

        self::assertCount(1, $histories);
        self::assertIsArray($histories[0]);
        self::assertSame(1, $histories[0]['id']);
        self::assertNotNull($histories[0]['deleted']);

        self::assertCount(4, $database->select("SELECT * FROM players"));
    }
}
