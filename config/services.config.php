<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command;
use Jesperbeisner\Fwstats\Controller;
use Jesperbeisner\Fwstats\Repository;
use Jesperbeisner\Fwstats\Service;
use Jesperbeisner\Fwstats\Stdlib;
use Psr\Log\LoggerInterface;

return [
    // Controller
    Controller\HomeController::class => Controller\Factory\HomeControllerFactory::class,

    // Services
    Service\FreewarDumpServiceInterface::class => Service\Factory\FreewarDumpServiceFactory::class,

    // Commands
    Command\DatabaseMigrationCommand::class => Command\Factory\DatabaseMigrationCommandFactory::class,
    Command\ImportWorldStatsCommand::class => Command\Factory\ImportWorldStatsCommandFactory::class,

    // Repositories
    Repository\MigrationRepository::class => Repository\Factory\RepositoryFactory::class,

    Repository\PlayerRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerNameHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerRaceHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerProfessionHistoryRepository::class => Repository\Factory\RepositoryFactory::class,

    Repository\ClanRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\ClanNamingHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\ClanCreatedHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\ClanDeletedHistoryRepository::class => Repository\Factory\RepositoryFactory::class,

    // Stdlib
    Stdlib\Request::class => Stdlib\Factory\RequestFactory::class,
    Stdlib\Router::class => Stdlib\Factory\RouterFactory::class,

    PDO::class => Stdlib\Factory\PdoFactory::class,
    LoggerInterface::class => Stdlib\Factory\LoggerFactory::class,
];
