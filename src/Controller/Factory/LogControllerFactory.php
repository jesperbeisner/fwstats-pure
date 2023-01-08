<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LogController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\LogRepository;

final readonly class LogControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LogController
    {
        /** @var LogRepository $logRepository */
        $logRepository = $container->get(LogRepository::class);

        return new LogController($logRepository);
    }
}
