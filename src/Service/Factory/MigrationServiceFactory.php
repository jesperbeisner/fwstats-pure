<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use Jesperbeisner\Fwstats\Service\MigrationService;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class MigrationServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): MigrationService
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $migrationsDirectory = $config->getString('migrations_directory');

        /** @var MigrationRepository $migrationRepository */
        $migrationRepository = $container->get(MigrationRepository::class);

        return new MigrationService($migrationsDirectory, $migrationRepository);
    }
}
