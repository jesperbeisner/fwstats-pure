<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\CronjobController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\CronjobInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class CronjobControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): CronjobController
    {
        return new CronjobController(
            $container->get(CronjobInterface::class),
        );
    }
}
