<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\DTO;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class PlayerClanHistory
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $playerId,
        public readonly ?int $oldClanId,
        public readonly ?int $newClanId,
        public readonly ?string $oldShortcut,
        public readonly ?string $newShortcut,
        public readonly ?string $oldName,
        public readonly ?string $newName,
    ) {
    }
}
