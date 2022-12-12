<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Middleware\Factory;

use Jesperbeisner\Fwstats\Middleware\DatabaseRequestLoggerMiddleware;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final class DatabaseRequestLoggerMiddlewareFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseRequestLoggerMiddleware
    {
        /** @var Request $request */
        $request = $container->get(Request::class);

        /** @var LogRepository $logRepository */
        $logRepository = $container->get(LogRepository::class);

        return new DatabaseRequestLoggerMiddleware(
            $request,
            $logRepository,
        );
    }
}
