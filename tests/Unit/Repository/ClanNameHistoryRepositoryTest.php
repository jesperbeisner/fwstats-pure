<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\ClanNameHistory;
use Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository
 */
final class ClanNameHistoryRepositoryTest extends AbstractTestCase
{
    use ContainerTrait;

    private ClanNameHistoryRepository $clanNameHistoryRepository;

    protected function setUp(): void
    {
        $this->setUpContainer();
        $this->loadMigrations();

        $this->clanNameHistoryRepository = $this->getContainer()->get(ClanNameHistoryRepository::class);
    }

    public function test_insert(): void
    {
        $clanNameHistory = new ClanNameHistory(null, WorldEnum::AFSRV, 1, 'o.O', 'o.O', 'test1', 'test2', new DateTimeImmutable());
        $newClanNameHistory = $this->clanNameHistoryRepository->insert($clanNameHistory);

        self::assertSame(1, $newClanNameHistory->id);
        self::assertNotSame($clanNameHistory, $newClanNameHistory);
    }
}
