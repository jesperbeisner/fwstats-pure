<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;

final readonly class Migration
{
    public function __construct(
        public ?int $id,
        public string $name,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, Migration $migration): Migration
    {
        return new Migration($id, $migration->name, $migration->created);
    }
}
