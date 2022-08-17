<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

abstract class AbstractCommand
{
    protected const SUCCESS = 0;
    protected const FAILURE = 1;

    public static string $name = '';
    public static string $description = '';

    abstract public function execute(): int;

    protected function write(string $text): void
    {
        echo $text . PHP_EOL;
    }
}
