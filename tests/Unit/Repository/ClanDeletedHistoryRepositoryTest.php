<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\ClanDeletedHistory;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository
 */
final class ClanDeletedHistoryRepositoryTest extends AbstractTestCase
{
    use ContainerTrait;

    private ClanDeletedHistoryRepository $clanDeletedHistoryRepository;

    protected function setUp(): void
    {
        $this->setUpContainer();
        $this->loadMigrations();

        $this->clanDeletedHistoryRepository = $this->getContainer()->get(ClanDeletedHistoryRepository::class);
    }

    public function test_insert(): void
    {
        $clanDeletedHistory = new ClanDeletedHistory(null, WorldEnum::AFSRV, 1, 'o.O', 'test', 1, 1, 1, 1, new DateTimeImmutable());
        $newClanDeletedHistory = $this->clanDeletedHistoryRepository->insert($clanDeletedHistory);

        self::assertSame(1, $newClanDeletedHistory->id);
        self::assertNotSame($clanDeletedHistory, $newClanDeletedHistory);
    }
}
