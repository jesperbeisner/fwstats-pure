<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Service\MigrationService;
use Jesperbeisner\Fwstats\Service\SetupService;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class SetupServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SetupService
    {
        return new SetupService(
            $container->get(Config::class)->getString('setup_file'),
            $container->get(MigrationService::class),
            $container->get(CreateUserAction::class),
        );
    }
}
