<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\DatabaseMigrationCommand;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class DatabaseMigrationCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): DatabaseMigrationCommand
    {
        /** @var MigrationRepository $migrationRepository */
        $migrationRepository = $serviceContainer->get(MigrationRepository::class);

        return new DatabaseMigrationCommand($migrationRepository);
    }
}
