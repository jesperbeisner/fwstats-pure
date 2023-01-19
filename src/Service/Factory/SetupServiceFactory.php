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
        /** @var Config $config */
        $config = $container->get(Config::class);

        $setupFileName = $config->getString('setup_file');

        /** @var MigrationService $migrationService */
        $migrationService = $container->get(MigrationService::class);

        /** @var CreateUserAction $createUserAction */
        $createUserAction = $container->get(CreateUserAction::class);

        return new SetupService($setupFileName, $migrationService, $createUserAction);
    }
}
