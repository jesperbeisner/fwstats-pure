<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\DTO;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class ClanNamingHistory
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $clanId,
        public readonly string $oldShortcut,
        public readonly string $newShortcut,
        public readonly string $oldName,
        public readonly string $newName,
    ) {
    }
}
