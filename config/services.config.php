<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command\Factory;
use Jesperbeisner\Fwstats\Controller;
use Jesperbeisner\Fwstats\Stdlib;
use Psr\Log\LoggerInterface;

return [
    // Controller
    Controller\HomeController::class => Controller\Factory\HomeControllerFactory::class,

    // Services

    // Commands
    'app:database-migration' => Factory\DatabaseMigrationCommandFactory::class,
    'app:import-players' => Factory\ImportPlayersCommandFactory::class,

    // Stdlib
    Stdlib\Request::class => Stdlib\Factory\RequestFactory::class,
    Stdlib\Router::class => Stdlib\Factory\RouterFactory::class,
    Stdlib\Database::class => Stdlib\Factory\DatabaseFactory::class,
    LoggerInterface::class => Stdlib\Factory\LoggerFactory::class,
];
