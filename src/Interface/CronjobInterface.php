<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

interface CronjobInterface
{
    public function isAllowedToRun(): bool;

    public function run(): void;
}
