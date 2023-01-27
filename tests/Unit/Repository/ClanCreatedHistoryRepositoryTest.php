<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\ClanCreatedHistory;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository
 */
final class ClanCreatedHistoryRepositoryTest extends AbstractTestCase
{
    use ContainerTrait;

    private ClanCreatedHistoryRepository $clanCreatedHistoryRepository;

    protected function setUp(): void
    {
        $this->setUpContainer();
        $this->loadMigrations();

        $this->clanCreatedHistoryRepository = $this->getContainer()->get(ClanCreatedHistoryRepository::class);
    }

    public function test_insert(): void
    {
        $clanCreatedHistory = new ClanCreatedHistory(null, WorldEnum::AFSRV, 1, 'o.O', 'test', 1, 1, 1, 1, new DateTimeImmutable());
        $newClanCreatedHistory = $this->clanCreatedHistoryRepository->insert($clanCreatedHistory);

        self::assertSame(1, $newClanCreatedHistory->id);
        self::assertNotSame($clanCreatedHistory, $newClanCreatedHistory);
    }
}
