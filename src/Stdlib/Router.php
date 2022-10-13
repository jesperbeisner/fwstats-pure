<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use FastRoute;
use Jesperbeisner\Fwstats\Stdlib\Exception\RouterException;

final class Router
{
    private readonly array $routes;

    public function __construct(string $routesFile)
    {
        if (!file_exists($routesFile)) {
            throw new RouterException(sprintf('The provided routes file "%s" does not exist.', $routesFile));
        }

        $routesArray = require $routesFile;

        if (!is_array($routesArray)) {
            throw new RouterException(sprintf('The provided routes config file "%s" did not return an array.', $routesFile));
        }

        $this->routes = $routesArray;
    }

    /**
     * @return mixed[]
     */
    public function match(Request $request): array
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector) {
            foreach ($this->routes as $route) {
                $routeCollector->addRoute($route['methods'], $route['route'], [$route['controller'], $route['action']]);
            }
        });

        return $dispatcher->dispatch($request->httpMethod, $request->uri);
    }
}
