<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;
use Psr\Log\AbstractLogger;
use Stringable;

final class Logger extends AbstractLogger
{
    public function __construct(
        private readonly string $rootDir,
    ) {
    }

    /**
     * @param mixed[] $context
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        if (!is_string($level)) {
            throw new RuntimeException('$level needs to be a string.');
        }

        $jsonContext = json_encode($context, JSON_THROW_ON_ERROR);
        $message = '[' . date('Y-m-d H:i:s') . '] ' . strtoupper($level) . ': ' . $message . ' ' . $jsonContext . PHP_EOL;

        if (false === $outputStream = fopen($this->rootDir . '/data/logs/fwstats.log', 'a')) {
            throw new RuntimeException('Could not open stdout resource');
        }

        fwrite($outputStream, $message);
        fclose($outputStream);
    }
}
