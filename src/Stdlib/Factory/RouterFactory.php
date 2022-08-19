<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Router;
use Psr\Container\ContainerInterface;

final class RouterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): Router
    {
        /** @var mixed[] $globalConfig */
        $globalConfig = $serviceContainer->get('config');

        /** @var array<int, array{route: string, methods: string[], controller: string[]}> $routesConfig */
        $routesConfig = $globalConfig['routes'];

        /** @var Request $request */
        $request = $serviceContainer->get(Request::class);

        return new Router($routesConfig, $request);
    }
}
