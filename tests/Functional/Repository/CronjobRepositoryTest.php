<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Functional\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Cronjob;
use Jesperbeisner\Fwstats\Repository\CronjobRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Repository\CronjobRepository
 */
final class CronjobRepositoryTest extends AbstractTestCase
{
    private CronjobRepository $cronjobRepository;

    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();

        $this->cronjobRepository = self::getContainer()->get(CronjobRepository::class);
    }

    public function test_insert(): void
    {
        $cronjob = new Cronjob(null, new DateTimeImmutable());
        $newCronjob = $this->cronjobRepository->insert($cronjob);

        self::assertSame(1, $newCronjob->id);
        self::assertNotSame($cronjob, $newCronjob);
    }

    public function test_findLastCronjob_returns_null_when_no_last_cronjob_exists(): void
    {
        $cronjob = $this->cronjobRepository->findLastCronjob();

        self::assertNull($cronjob);
    }

    public function test_findLastCronjob_returns_the_last_cronjob(): void
    {
        $cronjobs = [
            new Cronjob(null, new DateTimeImmutable('2022-01-01 00:00:00')),
            new Cronjob(null, new DateTimeImmutable('2021-01-01 00:00:00')),
            new Cronjob(null, new DateTimeImmutable('2020-01-01 00:00:00')),
        ];

        foreach ($cronjobs as $cronjob) {
            $this->cronjobRepository->insert($cronjob);
        }

        $cronjob = $this->cronjobRepository->findLastCronjob();

        self::assertNotNull($cronjob);
        self::assertSame('2022-01-01 00:00:00', $cronjob->created->format('Y-m-d H:i:s'));
    }
}
