<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command;

return [
    Command\DatabaseMigrationCommand::class,
    Command\DatabaseFixtureCommand::class,
    Command\AppCommand::class,
];
