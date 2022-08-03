<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command\AbstractCommand;
use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;

/** @var ServiceContainer $serviceContainer */
$serviceContainer = require __DIR__ . '/../bootstrap.php';

if (!isset($argv[1])) {
    echo 'Error: No command name to run specified.' . PHP_EOL;
    exit(1);
}

$commandName = $argv[1];

if (!$serviceContainer->has($commandName)) {
    echo 'Error: Command name not found in the service container. Did you forget to register it?' . PHP_EOL;
    exit(1);
}

/** @var AbstractCommand $command */
$command = $serviceContainer->get($commandName);
exit($command->execute());
