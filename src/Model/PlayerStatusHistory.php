<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerStatusHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public string $name,
        public PlayerStatusEnum $status,
        public DateTimeImmutable $created,
        public ?DateTimeImmutable $deleted,
        public DateTimeImmutable $updated,
    ) {
    }

    public static function withId(int $id, PlayerStatusHistory $playerStatusHistory): PlayerStatusHistory
    {
        return new PlayerStatusHistory(
            $id,
            $playerStatusHistory->world,
            $playerStatusHistory->playerId,
            $playerStatusHistory->name,
            $playerStatusHistory->status,
            $playerStatusHistory->created,
            $playerStatusHistory->deleted,
            $playerStatusHistory->updated,
        );
    }
}
