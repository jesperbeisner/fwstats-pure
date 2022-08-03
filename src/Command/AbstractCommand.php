<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

abstract class AbstractCommand
{
    protected const SUCCESS = 0;
    protected const FAILURE = 1;

    abstract public function execute(): int;
}
