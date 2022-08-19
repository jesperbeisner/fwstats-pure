<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;
use JsonException;
use Psr\Log\AbstractLogger;
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

        try {
            $jsonContext = json_encode($context, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Could not encode json.');
        }

        $message = '[' . date('Y-m-d H:i:s') . '] ' . strtoupper($level) . ': ' . $message . ' ' . $jsonContext . PHP_EOL;

        if (false === $outputStream = fopen('php://stderr', 'w')) {
            throw new RuntimeException('Could not open stdout resource');
        }

        fwrite($outputStream, $message);
        fclose($outputStream);
    }
}
