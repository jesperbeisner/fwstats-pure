<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\LoggerInterface;
use Jesperbeisner\Fwstats\Process\ExceptionHandlerProcess;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class ExceptionHandlerProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ExceptionHandlerProcess
    {
        return new ExceptionHandlerProcess(
            $container->get(LoggerInterface::class),
            $container->get(Config::class)->getAppEnv(),
        );
    }
}
