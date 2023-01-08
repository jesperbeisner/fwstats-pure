<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\StatusController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class StatusControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): StatusController
    {
        return new StatusController();
    }
}
