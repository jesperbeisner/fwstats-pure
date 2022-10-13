<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\DatabaseMigrationCommand;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

class DatabaseMigrationCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseMigrationCommand
    {
        /** @var MigrationRepository $migrationRepository */
        $migrationRepository = $container->get(MigrationRepository::class);

        return new DatabaseMigrationCommand($migrationRepository);
    }
}
