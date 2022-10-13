<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Service\FreewarDumpService;
use Jesperbeisner\Fwstats\Service\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

class FreewarDumpServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): FreewarDumpServiceInterface
    {
        return new FreewarDumpService();
    }
}
