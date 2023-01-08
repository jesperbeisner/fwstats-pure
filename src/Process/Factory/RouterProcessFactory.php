<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\RouterInterface;
use Jesperbeisner\Fwstats\Process\RouterProcess;

final readonly class RouterProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RouterProcess
    {
        /** @var RouterInterface $router */
        $router = $container->get(RouterInterface::class);

        return new RouterProcess($router);
    }
}
