<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LogoutController;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;

final class LogoutControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LogoutController
    {
        /** @var SessionInterface $session */
        $session = $container->get(SessionInterface::class);

        return new LogoutController(
            $session,
        );
    }
}
