<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Functional\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Achievement;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Repository\AchievementRepository
 */
final class AchievementRepositoryTest extends AbstractTestCase
{
    private AchievementRepository $achievementRepository;

    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();

        $this->achievementRepository = self::getContainer()->get(AchievementRepository::class);
    }

    public function test_insert(): void
    {
        $achievement = new Achievement(null, WorldEnum::AFSRV, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, new DateTimeImmutable());
        $newAchievement = $this->achievementRepository->insert($achievement);

        self::assertSame(1, $newAchievement->id);
        self::assertNotSame($achievement, $newAchievement);
    }

    public function test_findByPlayer_without_available_achievement(): void
    {
        $player = new Player(null, WorldEnum::AFSRV, 1, 'test', 'Onlo', 1, 1, 1, null, null, new DateTimeImmutable());

        $result = $this->achievementRepository->findByPlayer($player);

        self::assertNull($result);
    }

    public function test_findByPlayer_with_available_achievement(): void
    {
        $achievement = new Achievement(null, WorldEnum::AFSRV, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, new DateTimeImmutable());
        $this->achievementRepository->insert($achievement);

        $player = new Player(null, WorldEnum::AFSRV, 1, 'test', 'Onlo', 1, 1, 1, null, null, new DateTimeImmutable());

        $result = $this->achievementRepository->findByPlayer($player);

        self::assertInstanceOf(Achievement::class, $result);
        self::assertSame(1, $result->playerId);
    }
}
