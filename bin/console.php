<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command\AbstractCommand;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../bootstrap.php';

/** @var Config $config */
$config = $container->get(Config::class);

if (!isset($argv[1])) {
    echo 'Available commands:' . PHP_EOL . PHP_EOL;

    /** @var class-string<AbstractCommand> $commandString */
    foreach ($config->getCommands() as $commandString) {
        echo str_pad($commandString::$name, 25) . $commandString::$description . PHP_EOL;
    }

    exit(0);
}

$commandName = $argv[1];
$commandClass = null;

/** @var class-string<AbstractCommand> $commandString */
foreach ($config->getCommands() as $commandString) {
    if ($commandString::$name === $commandName) {
        $commandClass = $commandString;
        break;
    }
}

if ($commandClass === null) {
    echo "Command '$commandName' could not be found. Did you forget to register your command in 'config.php'?";
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
