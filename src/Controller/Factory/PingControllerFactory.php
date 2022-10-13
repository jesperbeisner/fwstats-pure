<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\PingController;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class PingControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PingController
    {
        return new PingController();
    }
}
