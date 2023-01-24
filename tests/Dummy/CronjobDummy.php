<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Dummy;

use Jesperbeisner\Fwstats\Interface\CronjobInterface;

final readonly class CronjobDummy implements CronjobInterface
{
    public function __construct(
        private bool $isAllowedToRun = true,
    ) {
    }

    public function isAllowedToRun(): bool
    {
        return $this->isAllowedToRun;
    }

    public function run(): void
    {
    }
}
