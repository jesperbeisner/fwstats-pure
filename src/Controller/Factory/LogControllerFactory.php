<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LogController;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Psr\Container\ContainerInterface;

final class LogControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): LogController
    {
        /** @var SessionInterface $session */
        $session = $serviceContainer->get(SessionInterface::class);

        /** @var LogRepository $logRepository */
        $logRepository = $serviceContainer->get(LogRepository::class);

        return new LogController(
            $session,
            $logRepository
        );
    }
}
