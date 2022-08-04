<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command;
use Jesperbeisner\Fwstats\Controller;
use Jesperbeisner\Fwstats\Stdlib;
use Psr\Log\LoggerInterface;

return [
    // Controller
    Controller\HomeController::class => Controller\Factory\HomeControllerFactory::class,

    // Services

    // Commands
    Command\DatabaseMigrationCommand::class => Command\Factory\DatabaseMigrationCommandFactory::class,
    Command\ImportPlayersCommand::class => Command\Factory\ImportPlayersCommandFactory::class,

    // Stdlib
    Stdlib\Request::class => Stdlib\Factory\RequestFactory::class,
    Stdlib\Router::class => Stdlib\Factory\RouterFactory::class,
    Stdlib\Database::class => Stdlib\Factory\DatabaseFactory::class,
    LoggerInterface::class => Stdlib\Factory\LoggerFactory::class,
];
