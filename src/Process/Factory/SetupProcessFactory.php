<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Process\SetupProcess;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class SetupProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SetupProcess
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $databaseSetupFileName = $config->getString('database_setup_file');
        $migrationsDirectory = $config->getString('migrations_directory');

        /** @var MigrationRepository $migrationRepository */
        $migrationRepository = $container->get(MigrationRepository::class);

        /** @var CreateUserAction $createUserAction */
        $createUserAction = $container->get(CreateUserAction::class);

        return new SetupProcess($databaseSetupFileName, $migrationsDirectory, $migrationRepository, $createUserAction);
    }
}
