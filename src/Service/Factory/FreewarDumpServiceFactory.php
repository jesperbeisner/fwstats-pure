<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Service\FreewarDumpService;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class FreewarDumpServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): FreewarDumpServiceInterface
    {
        return new FreewarDumpService();
    }
}
