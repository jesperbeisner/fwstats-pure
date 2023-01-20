<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;

final readonly class Config
{
    public function __construct(
        public ?int $id,
        public string $key,
        public string $value,
        public DateTimeImmutable $created,
    ) {
    }
}
