<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class PlayerNameHistory
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $playerId,
        public readonly string $oldName,
        public readonly string $newName,
    ) {
    }
}
