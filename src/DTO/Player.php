<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\DTO;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class Player
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $playerId,
        public readonly string $name,
        public readonly string $race,
        public readonly int $xp,
        public readonly int $soulXp,
        public readonly int $totalXp,
        public readonly ?int $clanId,
        public readonly ?string $profession,
    ) {
    }
}
