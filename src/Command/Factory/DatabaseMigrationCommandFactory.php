<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\DatabaseMigrationCommand;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use Jesperbeisner\Fwstats\Stdlib\Database;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class DatabaseMigrationCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $serviceName): DatabaseMigrationCommand
    {
        /** @var Database $database */
        $database = $container->get(Database::class);

        /** @var MigrationRepository $migrationRepository */
        $migrationRepository = $database->getRepository(MigrationRepository::class);

        return new DatabaseMigrationCommand($migrationRepository);
    }
}
