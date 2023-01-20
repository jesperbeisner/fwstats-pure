<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Action;
use Jesperbeisner\Fwstats\Command;
use Jesperbeisner\Fwstats\Controller;
use Jesperbeisner\Fwstats\Importer;
use Jesperbeisner\Fwstats\Interface;
use Jesperbeisner\Fwstats\Process;
use Jesperbeisner\Fwstats\Repository;
use Jesperbeisner\Fwstats\Service;
use Jesperbeisner\Fwstats\Stdlib;

return [
    'global' => [
        'app_env' => $_ENV['APP_ENV'],
        'root_directory' => dirname(__DIR__),
        'views_directory' => __DIR__ . '/../views',
        'migrations_directory' => __DIR__ . '/../data/migrations',
        'translations_directory' => __DIR__ . '/../data/translations',
        'fixtures_directory' => __DIR__ . '/../data/fixtures',
        'log_file' => __DIR__ . '/../var/fwstats.log',
        'database_file' => __DIR__ . '/../data/database/sqlite.db',
        'setup_file' => __DIR__ . '/../data/database/setup.txt',
    ],
    'routes' => [
        ['route' => '/', 'methods' => ['GET'], 'controller' => Controller\IndexController::class],
        ['route' => '/profile/{world}/{id}', 'methods' => ['GET'], 'controller' => Controller\ProfileController::class],
        ['route' => '/playtime', 'methods' => ['GET'], 'controller' => Controller\PlaytimeController::class],
        ['route' => '/changes/names', 'methods' => ['GET'], 'controller' => Controller\ChangeController::class],
        ['route' => '/images/ranking', 'methods' => ['GET'], 'controller' => Controller\ImageController::class],
        ['route' => '/images/{world}-ranking.png', 'methods' => ['GET'], 'controller' => Controller\ImageRenderController::class],
        ['route' => '/status', 'methods' => ['GET'], 'controller' => Controller\StatusController::class],
        ['route' => '/admin/logs', 'methods' => ['GET'], 'controller' => Controller\LogController::class],
        ['route' => '/login', 'methods' => ['GET', 'POST'], 'controller' => Controller\LoginController::class],
        ['route' => '/logout', 'methods' => ['GET', 'POST'], 'controller' => Controller\LogoutController::class],
        ['route' => '/cronjob', 'methods' => ['POST'], 'controller' => Controller\CronjobController::class],
        ['route' => '/search', 'methods' => ['GET'], 'controller' => Controller\SearchController::class],
        ['route' => '/admin', 'methods' => ['GET'], 'controller' => Controller\AdminController::class],
        ['route' => '/locale', 'methods' => ['GET'], 'controller' => Controller\LocaleController::class],
    ],
    'processes' => [
        Process\ExceptionHandlerProcess::class,
        Process\SetupProcess::class,
        Process\RequestLoggerProcess::class,
        Process\LocaleProcess::class,
        Process\RouterProcess::class,
        Process\SessionProcess::class,
        Process\SecurityProcess::class,
    ],
    'commands' => [
        Command\AppCommand::class,
        Command\CreateUserCommand::class,
        Command\DatabaseFixtureCommand::class,
        Command\DatabaseMigrationCommand::class,
    ],
    'factories' => [
        // Controller
        Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        Controller\ProfileController::class => Controller\Factory\ProfileControllerFactory::class,
        Controller\ImageController::class => Controller\Factory\ImageControllerFactory::class,
        Controller\ImageRenderController::class => Controller\Factory\ImageRenderControllerFactory::class,
        Controller\PlaytimeController::class => Controller\Factory\PlaytimeControllerFactory::class,
        Controller\StatusController::class => Controller\Factory\StatusControllerFactory::class,
        Controller\ChangeController::class => Controller\Factory\ChangeControllerFactory::class,
        Controller\LogController::class => Controller\Factory\LogControllerFactory::class,
        Controller\LoginController::class => Controller\Factory\LoginControllerFactory::class,
        Controller\LogoutController::class => Controller\Factory\LogoutControllerFactory::class,
        Controller\NotFoundController::class => Controller\Factory\NotFoundControllerFactory::class,
        Controller\MethodNotAllowedController::class => Controller\Factory\MethodNotAllowedControllerFactory::class,
        Controller\SecurityController::class => Controller\Factory\SecurityControllerFactory::class,
        Controller\UnauthorizedController::class => Controller\Factory\UnauthorizedControllerFactory::class,
        Controller\CronjobController::class => Controller\Factory\CronjobControllerFactory::class,
        Controller\SearchController::class => Controller\Factory\SearchControllerFactory::class,
        Controller\AdminController::class => Controller\Factory\AdminControllerFactory::class,
        Controller\LocaleController::class => Controller\Factory\LocaleControllerFactory::class,

        // Actions
        Action\CreateUserAction::class => Action\Factory\CreateUserActionFactory::class,

        // Services
        Interface\FreewarDumpServiceInterface::class => Service\Factory\FreewarDumpServiceFactory::class,
        Service\PlayerStatusService::class => Service\Factory\PlayerStatusServiceFactory::class,
        Service\PlaytimeService::class => Service\Factory\PlaytimeServiceFactory::class,
        Service\RenderService::class => Service\Factory\RenderServiceFactory::class,
        Service\CronjobService::class => Service\Factory\CronjobServiceFactory::class,
        Service\SetupService::class => Service\Factory\SetupServiceFactory::class,
        Service\MigrationService::class => Service\Factory\MigrationServiceFactory::class,

        // ImageService
        Service\RankingImageService::class => Service\Factory\RankingImageServiceFactory::class,

        // Commands
        Command\AppCommand::class => Command\Factory\AppCommandFactory::class,
        Command\CreateUserCommand::class => Command\Factory\CreateUserCommandFactory::class,
        Command\DatabaseFixtureCommand::class => Command\Factory\DatabaseFixtureCommandFactory::class,
        Command\DatabaseMigrationCommand::class => Command\Factory\DatabaseMigrationCommandFactory::class,

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
        Repository\LogRepository::class => Repository\Factory\RepositoryFactory::class,
        Repository\UserRepository::class => Repository\Factory\RepositoryFactory::class,
        Repository\CronjobRepository::class => Repository\Factory\RepositoryFactory::class,

        // Stdlib
        Interface\LoggerInterface::class => Stdlib\Factory\LoggerFactory::class,
        Interface\RouterInterface::class => Stdlib\Factory\RouterFactory::class,
        Interface\SessionInterface::class => Stdlib\Factory\SessionFactory::class,
        Interface\DatabaseInterface::class => Stdlib\Factory\DatabaseFactory::class,
        Interface\TranslatorInterface::class => Stdlib\Factory\TranslatorFactory::class,

        // Processes
        Process\ExceptionHandlerProcess::class => Process\Factory\ExceptionHandlerProcessFactory::class,
        Process\SetupProcess::class => Process\Factory\SetupProcessFactory::class,
        Process\RequestLoggerProcess::class => Process\Factory\RequestLoggerProcessFactory::class,
        Process\RouterProcess::class => Process\Factory\RouterProcessFactory::class,
        Process\SessionProcess::class => Process\Factory\SessionProcessFactory::class,
        Process\SecurityProcess::class => Process\Factory\SecurityProcessFactory::class,
        Process\LocaleProcess::class => Process\Factory\LocaleProcessFactory::class,
    ],
];
