<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Process\RequestLoggerEndProcess;
use Jesperbeisner\Fwstats\Repository\RequestLogRepository;

final readonly class RequestLoggerEndProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RequestLoggerEndProcess
    {
        return new RequestLoggerEndProcess(
            $container->get(RequestLogRepository::class),
        );
    }
}
