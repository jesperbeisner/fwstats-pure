<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerClanHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public ?int $oldClanId,
        public ?int $newClanId,
        public ?string $oldShortcut,
        public ?string $newShortcut,
        public ?string $oldName,
        public ?string $newName,
    ) {
    }

    public static function withId(int $id, PlayerClanHistory $playerClanHistory): PlayerClanHistory
    {
        return new PlayerClanHistory(
            $id,
            $playerClanHistory->world,
            $playerClanHistory->playerId,
            $playerClanHistory->oldClanId,
            $playerClanHistory->newClanId,
            $playerClanHistory->oldShortcut,
            $playerClanHistory->newShortcut,
            $playerClanHistory->oldName,
            $playerClanHistory->newName,
        );
    }
}
