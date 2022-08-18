<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use RuntimeException;

abstract class AbstractCommand
{
    protected const SUCCESS = 0;
    protected const FAILURE = 1;

    public static string $name = '';
    public static string $description = '';

    protected ?float $time = null;

    abstract public function execute(): int;

    protected function write(string $text): void
    {
        echo $text . PHP_EOL;
    }

    protected function startTime(): void
    {
        $this->time = microtime(true);
    }

    protected function getTime(): int
    {
        if ($this->time === null) {
            throw new RuntimeException("Did you forget to call 'startTime' before calling 'getTime'?");
        }

        return (int) round((microtime(true) - $this->time) * 1000);
    }
}
