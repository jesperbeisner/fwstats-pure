<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class ClanNameHistory
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
