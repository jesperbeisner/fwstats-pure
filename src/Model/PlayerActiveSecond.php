<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class PlayerActiveSecond
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $playerId,
        public readonly int $seconds,
        public readonly DateTimeImmutable $created
    ) {
    }
}
