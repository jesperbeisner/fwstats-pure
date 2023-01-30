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
        'domain' => 'https://fwstats.de',
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
        ['route' => '/profile/{world}/{player-id}', 'methods' => ['GET'], 'controller' => Controller\ProfileController::class],
        ['route' => '/playtime', 'methods' => ['GET'], 'controller' => Controller\PlaytimeController::class],
        ['route' => '/xp-changes', 'methods' => ['GET'], 'controller' => Controller\XpChangeController::class],
        ['route' => '/changes/names', 'methods' => ['GET'], 'controller' => Controller\NameChangeController::class],
        ['route' => '/changes/races', 'methods' => ['GET'], 'controller' => Controller\RaceChangeController::class],
        ['route' => '/changes/professions', 'methods' => ['GET'], 'controller' => Controller\ProfessionChangeController::class],
        ['route' => '/images/ranking', 'methods' => ['GET'], 'controller' => Controller\RankingImageController::class],
        ['route' => '/images/{world}-ranking.png', 'methods' => ['GET'], 'controller' => Controller\RankingImageDisplayController::class],
        ['route' => '/images/changes/names', 'methods' => ['GET'], 'controller' => Controller\NameChangeImageController::class],
        ['route' => '/images/{world}-name-changes.png', 'methods' => ['GET'], 'controller' => Controller\NameChangeImageDisplayController::class],
        ['route' => '/images/changes/races', 'methods' => ['GET'], 'controller' => Controller\RaceChangeImageController::class],
        ['route' => '/images/{world}-race-changes.png', 'methods' => ['GET'], 'controller' => Controller\RaceChangeImageDisplayController::class],
        ['route' => '/status', 'methods' => ['GET'], 'controller' => Controller\StatusController::class],
        ['route' => '/login', 'methods' => ['GET', 'POST'], 'controller' => Controller\LoginController::class],
        ['route' => '/logout', 'methods' => ['GET', 'POST'], 'controller' => Controller\LogoutController::class],
        ['route' => '/cronjob', 'methods' => ['POST'], 'controller' => Controller\CronjobController::class],
        ['route' => '/search', 'methods' => ['GET'], 'controller' => Controller\SearchController::class],
        ['route' => '/locale', 'methods' => ['GET'], 'controller' => Controller\LocaleController::class],
        ['route' => '/admin', 'methods' => ['GET'], 'controller' => Controller\AdminController::class],
        ['route' => '/admin/change-password', 'methods' => ['POST'], 'controller' => Controller\ChangePasswordController::class],
        ['route' => '/admin/generate-new-bearer-token', 'methods' => ['POST'], 'controller' => Controller\GenerateNewBearerTokenController::class],
        ['route' => '/admin/change-domain-name', 'methods' => ['POST'], 'controller' => Controller\ChangeDomainNameController::class],
        ['route' => '/admin/reset-action-freewar', 'methods' => ['POST'], 'controller' => Controller\ResetActionFreewarController::class],
        ['route' => '/admin/request-logs', 'methods' => ['GET'], 'controller' => Controller\RequestLogController::class],
    ],
    'startProcesses' => [
        Process\ExceptionHandlerStartProcess::class,
        Process\SetupStartProcess::class,
        Process\LocaleStartProcess::class,
        Process\RouterStartProcess::class,
        Process\SessionStartProcess::class,
        Process\SecurityStartProcess::class,
    ],
    'endProcesses' => [
        Process\RequestLoggerEndProcess::class,
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
        Controller\RankingImageController::class => Controller\Factory\RankingImageControllerFactory::class,
        Controller\RankingImageDisplayController::class => Controller\Factory\RankingImageDisplayControllerFactory::class,
        Controller\NameChangeImageController::class => Controller\Factory\NameChangeImageControllerFactory::class,
        Controller\NameChangeImageDisplayController::class => Controller\Factory\NameChangeImageDisplayControllerFactory::class,
        Controller\RaceChangeImageController::class => Controller\Factory\RaceChangeImageControllerFactory::class,
        Controller\RaceChangeImageDisplayController::class => Controller\Factory\RaceChangeImageDisplayControllerFactory::class,
        Controller\PlaytimeController::class => Controller\Factory\PlaytimeControllerFactory::class,
        Controller\StatusController::class => Controller\Factory\StatusControllerFactory::class,
        Controller\NameChangeController::class => Controller\Factory\NameChangeControllerFactory::class,
        Controller\RaceChangeController::class => Controller\Factory\RaceChangeControllerFactory::class,
        Controller\ProfessionChangeController::class => Controller\Factory\ProfessionChangeControllerFactory::class,
        Controller\RequestLogController::class => Controller\Factory\RequestLogControllerFactory::class,
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
        Controller\ChangePasswordController::class => Controller\Factory\ChangePasswordControllerFactory::class,
        Controller\GenerateNewBearerTokenController::class => Controller\Factory\GenerateNewBearerTokenControllerFactory::class,
        Controller\ChangeDomainNameController::class => Controller\Factory\ChangeDomainNameControllerFactory::class,
        Controller\ResetActionFreewarController::class => Controller\Factory\ResetActionFreewarControllerFactory::class,
        Controller\XpChangeController::class => Controller\Factory\XpChangeControllerFactory::class,

        // Actions
        Action\CreateUserAction::class => Action\Factory\CreateUserActionFactory::class,
        Action\ChangePasswordAction::class => Action\Factory\ChangePasswordActionFactory::class,
        Action\GenerateNewBearerTokenAction::class => Action\Factory\GenerateNewBearerTokenActionFactory::class,
        Action\ChangeDomainNameAction::class => Action\Factory\ChangeDomainNameActionFactory::class,
        Action\ResetActionFreewarAction::class => Action\Factory\ResetActionFreewarActionFactory::class,

        // Services
        Interface\FreewarDumpServiceInterface::class => Service\Factory\FreewarDumpServiceFactory::class,
        Service\PlayerStatusService::class => Service\Factory\PlayerStatusServiceFactory::class,
        Service\PlaytimeService::class => Service\Factory\PlaytimeServiceFactory::class,
        Service\RenderService::class => Service\Factory\RenderServiceFactory::class,
        Interface\CronjobInterface::class => Service\Factory\CronjobServiceFactory::class,
        Service\SetupService::class => Service\Factory\SetupServiceFactory::class,
        Service\MigrationService::class => Service\Factory\MigrationServiceFactory::class,
        Service\XpService::class => Service\Factory\XpServiceFactory::class,

        // ImageService
        Service\RankingImageService::class => Service\Factory\RankingImageServiceFactory::class,
        Service\NameChangeImageService::class => Service\Factory\NameChangeImageServiceFactory::class,
        Service\RaceChangeImageService::class => Service\Factory\RaceChangeImageServiceFactory::class,

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
        Repository\RequestLogRepository::class => Repository\Factory\RepositoryFactory::class,
        Repository\UserRepository::class => Repository\Factory\RepositoryFactory::class,
        Repository\CronjobRepository::class => Repository\Factory\RepositoryFactory::class,
        Repository\ConfigRepository::class => Repository\Factory\RepositoryFactory::class,
        Repository\PlayerXpHistoryRepository::class => Repository\Factory\RepositoryFactory::class,

        // Stdlib
        Interface\LoggerInterface::class => Stdlib\Factory\LoggerFactory::class,
        Interface\RouterInterface::class => Stdlib\Factory\RouterFactory::class,
        Interface\SessionInterface::class => Stdlib\Factory\SessionFactory::class,
        Interface\DatabaseInterface::class => Stdlib\Factory\DatabaseFactory::class,
        Interface\TranslatorInterface::class => Stdlib\Factory\TranslatorFactory::class,

        // Start Processes
        Process\ExceptionHandlerStartProcess::class => Process\Factory\ExceptionHandlerStartProcessFactory::class,
        Process\SetupStartProcess::class => Process\Factory\SetupStartProcessFactory::class,
        Process\RouterStartProcess::class => Process\Factory\RouterStartProcessFactory::class,
        Process\SessionStartProcess::class => Process\Factory\SessionStartProcessFactory::class,
        Process\SecurityStartProcess::class => Process\Factory\SecurityStartProcessFactory::class,
        Process\LocaleStartProcess::class => Process\Factory\LocaleStartProcessFactory::class,

        // End Processes
        Process\RequestLoggerEndProcess::class => Process\Factory\RequestLoggerEndProcessFactory::class,
    ],
];
