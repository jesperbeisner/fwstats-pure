<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\CronjobController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Service\CronjobService;

final readonly class CronjobControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): CronjobController
    {
        /** @var CronjobService $cronjobService */
        $cronjobService = $container->get(CronjobService::class);

        return new CronjobController($cronjobService);
    }
}
