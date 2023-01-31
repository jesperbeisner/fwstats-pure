<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerCreatedHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public string $name,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, PlayerCreatedHistory $playerCreatedHistory): PlayerCreatedHistory
    {
        return new PlayerCreatedHistory(
            $id,
            $playerCreatedHistory->world,
            $playerCreatedHistory->playerId,
            $playerCreatedHistory->name,
            $playerCreatedHistory->created,
        );
    }
}
