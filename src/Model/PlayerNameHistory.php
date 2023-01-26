<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerNameHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public string $oldName,
        public string $newName,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, PlayerNameHistory $playerNameHistory): PlayerNameHistory
    {
        return new PlayerNameHistory(
            $id,
            $playerNameHistory->world,
            $playerNameHistory->playerId,
            $playerNameHistory->oldName,
            $playerNameHistory->newName,
            $playerNameHistory->created,
        );
    }
}
