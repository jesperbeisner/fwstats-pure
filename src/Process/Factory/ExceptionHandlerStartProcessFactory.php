<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\LoggerInterface;
use Jesperbeisner\Fwstats\Process\ExceptionHandlerStartProcess;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class ExceptionHandlerStartProcessFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ExceptionHandlerStartProcess
    {
        return new ExceptionHandlerStartProcess(
            $container->get(LoggerInterface::class),
            $container->get(Config::class)->getAppEnv(),
        );
    }
}
