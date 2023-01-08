<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Exception\LoggerException;
use Jesperbeisner\Fwstats\Interface\LoggerInterface;
use JsonException;

final readonly class Logger implements LoggerInterface
{
    public function __construct(
        private string $logFile,
    ) {
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }

    private function log(string $level, string $message, array $context = []): void
    {
        try {
            $context = json_encode($context, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new LoggerException($e->getMessage());
        }

        if (false === $dateTime = DateTimeImmutable::createFromFormat('U.u', number_format(microtime(true), 6, '.', ''))) {
            throw new LoggerException('DateTimeImmutable::createFromFormat returned false.');
        }

        $logMessage = sprintf('[%s] %s: %s - Context: %s', $dateTime->format('Y-m-d H:i:s.u'), $level, $message, $context) . PHP_EOL;

        if (false === $file = fopen($this->logFile, 'a')) {
            throw new LoggerException(sprintf('Could not open log file "%s".', $this->logFile));
        }

        if (false === fwrite($file, $logMessage)) {
            throw new LoggerException(sprintf('Could not write to log file "%s".', $this->logFile));
        }

        if (false === fclose($file)) {
            throw new LoggerException(sprintf('Could not close log file "%s".', $this->logFile));
        }
    }
}
