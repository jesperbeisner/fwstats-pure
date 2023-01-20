<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Process\SetupProcess;
use Jesperbeisner\Fwstats\Service\SetupService;

final readonly class SetupProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SetupProcess
    {
        return new SetupProcess(
            $container->get(SetupService::class),
        );
    }
}
