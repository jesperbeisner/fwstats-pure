<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerActiveSecond
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public int $seconds,
        public DateTimeImmutable $created
    ) {
    }

    public static function withId(int $id, PlayerActiveSecond $playerActiveSecond): PlayerActiveSecond
    {
        return new PlayerActiveSecond(
            $id,
            $playerActiveSecond->world,
            $playerActiveSecond->playerId,
            $playerActiveSecond->seconds,
            $playerActiveSecond->created
        );
    }
}
