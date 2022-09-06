<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;

final class User
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $email,
        public readonly string $password,
        public readonly DateTimeImmutable $created
    ) {
    }
}
