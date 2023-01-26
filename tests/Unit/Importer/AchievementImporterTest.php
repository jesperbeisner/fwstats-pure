<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Importer;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\Doubles\DatabaseDummy;
use Jesperbeisner\Fwstats\Tests\Doubles\FreewarDumpServiceDummy;

/**
 * @covers \Jesperbeisner\Fwstats\Importer\AchievementImporter
 */
final class AchievementImporterTest extends AbstractTestCase
{
    public function test_it_works(): void
    {
        $database = new DatabaseDummy();
        $database->setSelectReturn([
            [
                'id' => 1,
                'world' => 'afsrv',
                'player_id' => 1,
                'name' => 'Test',
                'race' => 'Onlo',
                'xp' => 1,
                'soul_xp' => 0,
                'total_xp' => 1,
                'clan_id' => null,
                'profession' => null,
                'created' => '2023-01-01 00:00:00',
            ],
        ]);

        $freewarDumpService = new FreewarDumpServiceDummy([], [], [
            1 => [
                1019 => 100,
                1022 => 100,
                1021 => 100,
                1047 => 100,
                1008 => 100,
                1000 => 100,
            ],
        ]);

        $playerRepository = new PlayerRepository($database);
        $achievementRepository = new AchievementRepository($database);
        $achievementImporter = new AchievementImporter($freewarDumpService, $playerRepository, $achievementRepository);

        $result = $achievementImporter->import(WorldEnum::AFSRV);

        self::assertSame(['Starting AchievementImporter...', 'Finishing AchievementImporter...'], $result->getMessages());
    }
}
