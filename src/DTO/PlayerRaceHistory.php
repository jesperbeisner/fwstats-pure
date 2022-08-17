<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\DTO;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class PlayerRaceHistory
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $playerId,
        public readonly string $oldRace,
        public readonly string $newRace,
    ) {
    }
}
