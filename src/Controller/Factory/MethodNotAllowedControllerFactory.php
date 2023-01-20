<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\MethodNotAllowedController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class MethodNotAllowedControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): MethodNotAllowedController
    {
        return new MethodNotAllowedController();
    }
}
