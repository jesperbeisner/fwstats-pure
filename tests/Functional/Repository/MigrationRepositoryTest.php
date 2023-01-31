<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Functional\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Migration;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Repository\MigrationRepository
 */
final class MigrationRepositoryTest extends AbstractTestCase
{
    private MigrationRepository $migrationRepository;

    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();

        $this->migrationRepository = self::getContainer()->get(MigrationRepository::class);
    }

    public function test_insert(): void
    {
        $migration = new Migration(null, 'test', new DateTimeImmutable());
        $newMigration = $this->migrationRepository->insert($migration);

        self::assertNotNull($newMigration->id);
        self::assertNotSame($migration, $newMigration);
    }

    public function test_findByFileName_returns_null_when_file_name_does_not_exist(): void
    {
        $migration = $this->migrationRepository->findByFileName('test');

        self::assertNull($migration);
    }

    public function test_findByFileName_returns_the_migration_when_name_exists(): void
    {
        $this->migrationRepository->insert(new Migration(null, 'test', new DateTimeImmutable()));

        $migration = $this->migrationRepository->findByFileName('test');

        self::assertNotNull($migration);
        self::assertNotNull($migration->id);
    }
}
