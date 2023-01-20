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
        return new MigrationService(
            $container->get(Config::class)->getString('migrations_directory'),
            $container->get(MigrationRepository::class),
        );
    }
}
