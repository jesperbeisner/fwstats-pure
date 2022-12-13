<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use FastRoute;
use Jesperbeisner\Fwstats\Stdlib\Interface\RouterInterface;

final class Router implements RouterInterface
{
    /**
     * @param array<array{route: string, methods: array<string>, controller: string}> $routes
     */
    public function __construct(
        private readonly array $routes
    ) {
    }

    /**
     * TODO: Change return to object
     *
     * @return mixed[]
     */
    public function match(Request $request): array
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector) {
            foreach ($this->routes as $route) {
                $routeCollector->addRoute($route['methods'], $route['route'], $route['controller']);
            }
        });

        return $dispatcher->dispatch($request->getHttpMethod(), $request->getUri());
    }
}
