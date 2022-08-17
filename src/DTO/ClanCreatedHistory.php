<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\DTO;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class ClanCreatedHistory
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $clanId,
        public readonly string $shortcut,
        public readonly string $name,
        public readonly int $leaderId,
        public readonly int $coLeaderId,
        public readonly int $diplomatId,
        public readonly int $warPoints,
    ) {
    }
}
