<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class Clan
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
        public readonly DateTimeImmutable $created,
    ) {
    }
}
