<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;

final readonly class Cronjob
{
    public function __construct(
        public ?int $id,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, Cronjob $cronjob): Cronjob
    {
        return new Cronjob(
            $id,
            $cronjob->created
        );
    }
}
