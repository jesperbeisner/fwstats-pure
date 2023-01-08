<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

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

        $databaseSetupFileName = $config->getRootDir() . '/var/setup';
        $migrationsFolder = $config->getRootDir() . '/migrations';

        $migrationRepository = $container->get(MigrationRepository::class);

        return new SetupProcess($databaseSetupFileName, $migrationsFolder, $migrationRepository);
    }
}
