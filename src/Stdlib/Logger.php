<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\LoggerException;
use Jesperbeisner\Fwstats\Stdlib\Interface\LoggerInterface;

final class Logger implements LoggerInterface
{
    private const LEVELS = ['INFO', 'ERROR'];

    public function __construct(
        private readonly Config $config,
    ) {
    }

    public function log(string $level, string $message, array $context = []): void
    {
        if (!in_array($level, static::LEVELS)) {
            throw new LoggerException(sprintf('"%s" is not a valid log level.', $level));
        }

        $jsonContext = json_encode($context, JSON_THROW_ON_ERROR);
        $message = '[' . date('Y-m-d H:i:s') . '] ' . strtoupper($level) . ': ' . $message . ' ' . $jsonContext . PHP_EOL;

        $logFile = $this->config->getRootDir() . '/data/logs/fwstats.log';

        if (false === $logResource = fopen($logFile, 'a')) {
            throw new LoggerException(sprintf('Could not open "%s".', $logFile));
        }

        fwrite($logResource, $message);
        fclose($logResource);
    }

    public function info(string $message, array $context = []): void
    {
        $this->log('INFO', $message, $context);
    }

    public function error(string $message, array $context = []): void
    {
        $this->log('ERROR', $message, $context);
    }
}
