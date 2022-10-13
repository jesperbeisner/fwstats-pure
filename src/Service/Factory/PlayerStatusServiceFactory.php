<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Service\PlayerStatusService;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

class PlayerStatusServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlayerStatusService
    {
        return new PlayerStatusService();
    }
}
