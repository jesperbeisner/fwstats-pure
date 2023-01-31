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

    public function test_will_import_players_dump_successfully(): void
    {
        $container = self::getContainer();
        $database = $container->get(DatabaseInterface::class);

        $container->set(PlayerStatusServiceInterface::class, new PlayerStatusServiceDummy(PlayerStatusEnum::BANNED));

        $freewarDumpService = new FreewarDumpServiceDummy([
            new Player(null, WorldEnum::AFSRV, 1, 'Test-1', 'Onlo', 100, 0, 100, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 2, 'Test-2', 'Natla - Händler', 200, 0, 200, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 3, 'Test-3', 'Keuroner', 300, 0, 300, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 4, 'Test-4', 'Taruner', 400, 0, 400, null, null, new DateTimeImmutable()),
            new Player(null, WorldEnum::AFSRV, 5, 'Test-5', 'Serum-Geist', 500, 0, 500, null, null, new DateTimeImmutable()),
        ]);

        $container->set(FreewarDumpServiceInterface::class, $freewarDumpService);

        self::assertCount(0, $database->select("SELECT * FROM players"));

        $container->get(PlayerImporter::class)->import(WorldEnum::AFSRV);

        self::assertCount(5, $database->select("SELECT * FROM players"));
    }
}
