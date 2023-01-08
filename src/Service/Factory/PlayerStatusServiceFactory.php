<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Service\PlayerStatusService;

class PlayerStatusServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlayerStatusService
    {
        return new PlayerStatusService();
    }
}
