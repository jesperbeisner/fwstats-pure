<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use FastRoute;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Router;

final readonly class RouterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): Router
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector) use ($config) {
            foreach ($config->getRoutes() as $route) {
                $routeCollector->addRoute($route['methods'], $route['route'], $route['controller']);
            }
        });

        return new Router($dispatcher);
    }
}
