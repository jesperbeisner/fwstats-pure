<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Process\SetupStartProcess;
use Jesperbeisner\Fwstats\Service\SetupService;

final readonly class SetupStartProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SetupStartProcess
    {
        return new SetupStartProcess(
            $container->get(SetupService::class),
        );
    }
}
