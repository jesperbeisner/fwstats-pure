<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\SecurityController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class SecurityControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SecurityController
    {
        return new SecurityController();
    }
}
