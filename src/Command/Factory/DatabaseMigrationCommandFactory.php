<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\DatabaseMigrationCommand;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class DatabaseMigrationCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseMigrationCommand
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $migrationsFolder = $config->getRootDir() . '/migrations';

        /** @var MigrationRepository $migrationRepository */
        $migrationRepository = $container->get(MigrationRepository::class);

        return new DatabaseMigrationCommand($migrationsFolder, $migrationRepository);
    }
}
