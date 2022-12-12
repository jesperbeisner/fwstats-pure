<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use FastRoute;

final class Router
{
    /**
     * @param array<array{route: string, methods: array<string>, controller: string, action: string}> $routes
     */
    public function __construct(
        private readonly array $routes
    ) {
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

        return $dispatcher->dispatch($request->getHttpMethod(), $request->getUri());
    }
}
