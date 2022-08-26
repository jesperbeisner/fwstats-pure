<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;

final class Log
{
    public function __construct(
        public readonly string $url,
        public readonly DateTimeImmutable $created,
    ) {
    }
}
