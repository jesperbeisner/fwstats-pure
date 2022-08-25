<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command;
use Jesperbeisner\Fwstats\Controller;
use Jesperbeisner\Fwstats\ImageService;
use Jesperbeisner\Fwstats\Importer;
use Jesperbeisner\Fwstats\Repository;
use Jesperbeisner\Fwstats\Service;
use Jesperbeisner\Fwstats\Stdlib;
use Psr\Log\LoggerInterface;

return [
    // Controller
    Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
    Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class,
    Controller\ImageController::class => Controller\Factory\ImageControllerFactory::class,
    Controller\PlaytimeController::class => Controller\Factory\PlaytimeControllerFactory::class,
    Controller\PingController::class => Controller\Factory\PingControllerFactory::class,
    Controller\ChangeController::class => Controller\Factory\ChangeControllerFactory::class,

    // Services
    Service\FreewarDumpServiceInterface::class => Service\Factory\FreewarDumpServiceFactory::class,
    Service\PlayerStatusService::class => Service\Factory\PlayerStatusServiceFactory::class,
    Service\PlaytimeService::class => Service\Factory\PlaytimeServiceFactory::class,

    // ImageService
    ImageService\RankingImageService::class => ImageService\Factory\RankingImageServiceFactory::class,

    // Commands
    Command\DatabaseMigrationCommand::class => Command\Factory\DatabaseMigrationCommandFactory::class,
    Command\AppCommand::class => Command\Factory\AppCommandFactory::class,

    // Importer
    Importer\ClanImporter::class => Importer\Factory\ClanImporterFactory::class,
    Importer\PlayerImporter::class => Importer\Factory\PlayerImporterFactory::class,
    Importer\AchievementImporter::class => Importer\Factory\AchievementImporterFactory::class,
    Importer\PlaytimeImporter::class => Importer\Factory\PlaytimeImporterFactory::class,

    // Repositories
    Repository\MigrationRepository::class => Repository\Factory\RepositoryFactory::class,

    Repository\PlayerRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerNameHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerRaceHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerClanHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerProfessionHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerStatusHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\PlayerActiveSecondRepository::class => Repository\Factory\RepositoryFactory::class,

    Repository\ClanRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\ClanNameHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\ClanCreatedHistoryRepository::class => Repository\Factory\RepositoryFactory::class,
    Repository\ClanDeletedHistoryRepository::class => Repository\Factory\RepositoryFactory::class,

    Repository\AchievementRepository::class => Repository\Factory\RepositoryFactory::class,

    // Stdlib
    PDO::class => Stdlib\Factory\PdoFactory::class,
    LoggerInterface::class => Stdlib\Factory\LoggerFactory::class,
    Stdlib\Request::class => Stdlib\Factory\RequestFactory::class,
    Stdlib\Router::class => Stdlib\Factory\RouterFactory::class,
];
