<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

final class LoggerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): LoggerInterface
    {
        return new Logger();
    }
}
