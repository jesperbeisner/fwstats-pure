<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Psr\Log\AbstractLogger;
use RuntimeException;
use Stringable;

final class Logger extends AbstractLogger
{
    /**
     * @param mixed[] $context
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        if (!is_string($level)) {
            throw new RuntimeException('$level needs to be a string.');
        }

        $jsonContext = json_encode($context);

        $message = '[' . date('Y-m-d H:i:s') . '] ' . strtoupper($level) . ': ' . $message . ' ' . $jsonContext . PHP_EOL;

        if (false === $outputStream = fopen('php://stdout', 'w')) {
            throw new RuntimeException('Could not open stdout resource');
        }

        fwrite($outputStream, $message);
        fclose($outputStream);
    }
}
