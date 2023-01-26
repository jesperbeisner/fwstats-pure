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

    public static function withId(int $id, Config $config): Config
    {
        return new Config(
            $id,
            $config->key,
            $config->value,
            $config->created,
        );
    }
}
