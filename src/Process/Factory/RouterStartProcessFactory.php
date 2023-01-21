<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\RouterInterface;
use Jesperbeisner\Fwstats\Process\RouterStartProcess;

final readonly class RouterStartProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RouterStartProcess
    {
        return new RouterStartProcess(
            $container->get(RouterInterface::class)
        );
    }
}
