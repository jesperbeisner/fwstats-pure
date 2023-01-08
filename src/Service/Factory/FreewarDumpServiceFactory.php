<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Service\FreewarDumpService;

class FreewarDumpServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): FreewarDumpServiceInterface
    {
        return new FreewarDumpService();
    }
}
