<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command\AbstractCommand;
use Jesperbeisner\Fwstats\Stdlib\Container;

/** @var Container $container */
$container = require __DIR__ . '/../bootstrap.php';

/** @var array<class-string<AbstractCommand>> $commands */
$commandStrings = require __DIR__ . '/../config/commands.php';

if (!isset($argv[1])) {
    echo 'Available commands: ' . PHP_EOL . PHP_EOL;

    /** @var class-string<AbstractCommand> $commandString */
    foreach ($commandStrings as $commandString) {
        echo $commandString::$name . "\t" . $commandString::$description . PHP_EOL;
    }

    exit(0);
}

$commandName = (string) $argv[1];
$commandClass = null;

/** @var class-string<AbstractCommand> $commandString */
foreach ($commandStrings as $commandString) {
    if ($commandString::$name === $commandName) {
        $commandClass = $commandString;
        break;
    }
}

if ($commandClass === null) {
    echo "Command '$commandName' could not be found. Did you forget to register your command in 'commands.config.php'?";
    exit(1);
}

if (!$container->has($commandClass)) {
    echo "Command '$commandClass' not found in the service container. Did you forget to register it?" . PHP_EOL;
    exit(1);
}

/** @var AbstractCommand $command */
$command = $container->get($commandClass);
$command->setArguments($argv);

exit($command->execute());
