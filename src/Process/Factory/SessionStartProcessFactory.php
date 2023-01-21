<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Process\SessionStartProcess;

final readonly class SessionStartProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SessionStartProcess
    {
        return new SessionStartProcess(
            $container->get(SessionInterface::class),
        );
    }
}
