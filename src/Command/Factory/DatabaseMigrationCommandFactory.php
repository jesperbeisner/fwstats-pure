<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\DatabaseMigrationCommand;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Service\MigrationService;

final readonly class DatabaseMigrationCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseMigrationCommand
    {
        return new DatabaseMigrationCommand(
            $container->get(MigrationService::class),
        );
    }
}
