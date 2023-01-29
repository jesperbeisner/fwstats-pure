<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerXpHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public int $startXp,
        public int $endXp,
        public DateTimeImmutable $day,
    ) {
    }

    public static function withId(int $id, PlayerXpHistory $playerXpHistory): PlayerXpHistory
    {
        return new PlayerXpHistory(
            $id,
            $playerXpHistory->world,
            $playerXpHistory->playerId,
            $playerXpHistory->startXp,
            $playerXpHistory->endXp,
            $playerXpHistory->day,
        );
    }
}
