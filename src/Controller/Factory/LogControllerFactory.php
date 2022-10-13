<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LogController;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;

final class LogControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LogController
    {
        /** @var SessionInterface $session */
        $session = $container->get(SessionInterface::class);

        /** @var LogRepository $logRepository */
        $logRepository = $container->get(LogRepository::class);

        return new LogController(
            $session,
            $logRepository
        );
    }
}
