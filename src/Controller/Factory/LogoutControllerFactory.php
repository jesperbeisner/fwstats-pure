<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LogoutController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;

final readonly class LogoutControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LogoutController
    {
        return new LogoutController(
            $container->get(SessionInterface::class),
        );
    }
}
