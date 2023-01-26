<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\DTO;

use Generator;
use Jesperbeisner\Fwstats\DTO\Playtime;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;

/**
 * @covers \Jesperbeisner\Fwstats\DTO\Playtime
 */
class PlaytimeTest extends AbstractTestCase
{
    /**
     * @dataProvider providePlaytimeData
     */
    public function test_right_hours_minutes_and_seconds_from_playtime(int $hours, int $minutes, int $seconds, int $playtime): void
    {
        $playtime = new Playtime(WorldEnum::AFSRV, 'Test', 1, $playtime);

        self::assertSame($hours, $playtime->getHours());
        self::assertSame($minutes, $playtime->getMinutes());
        self::assertSame($seconds, $playtime->getSeconds());
    }

    public function providePlaytimeData(): Generator
    {
        yield '0 hours, 0min, 0 seconds' => [0, 0, 0, 0];
        yield '0 hours, 0min, 1 seconds' => [0, 0, 1, 1];
        yield '0 hours, 1min, 1 seconds' => [0, 1, 1, 60 + 1];
        yield '1 hours, 1min, 1 seconds' => [1, 1, 1, 3600 + 60 + 1];
        yield '1 hours, 0min, 0 seconds' => [1, 0, 0, 3600];
        yield '0 hours, 1min, 0 seconds' => [0, 1, 0, 60];
        yield '10 hours, 25min, 15 seconds' => [10, 25, 15, (3600 * 10) + (60 * 25) + 15];
    }
}
