<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command;

return [
    Command\AppCommand::class,
    Command\CreateUserCommand::class,
    Command\DatabaseFixtureCommand::class,
    Command\DatabaseMigrationCommand::class,
];
