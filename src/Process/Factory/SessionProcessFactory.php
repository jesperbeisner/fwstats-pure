<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Process\SessionProcess;

final readonly class SessionProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SessionProcess
    {
        /** @var SessionInterface $session */
        $session = $container->get(SessionInterface::class);

        return new SessionProcess($session);
    }
}
