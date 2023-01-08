<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Process\RequestLoggerProcess;
use Jesperbeisner\Fwstats\Repository\LogRepository;

final readonly class RequestLoggerProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RequestLoggerProcess
    {
        /** @var LogRepository $logRepository */
        $logRepository = $container->get(LogRepository::class);

        return new RequestLoggerProcess($logRepository);
    }
}
