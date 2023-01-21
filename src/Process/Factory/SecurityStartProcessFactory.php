<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Process\SecurityStartProcess;
use Jesperbeisner\Fwstats\Repository\UserRepository;

final readonly class SecurityStartProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SecurityStartProcess
    {
        return new SecurityStartProcess(
            $container->get(SessionInterface::class),
            $container->get(UserRepository::class),
        );
    }
}
