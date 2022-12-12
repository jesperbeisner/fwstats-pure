<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Middleware\Factory;

use Jesperbeisner\Fwstats\Middleware\ExceptionHandlerMiddleware;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\LoggerInterface;

final class ExceptionHandlerMiddlewareFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ExceptionHandlerMiddleware
    {
        /** @var LoggerInterface $logger */
        $logger = $container->get(LoggerInterface::class);

        /** @var Config $config */
        $config = $container->get(Config::class);

        return new ExceptionHandlerMiddleware(
            $logger,
            $config,
        );
    }
}
