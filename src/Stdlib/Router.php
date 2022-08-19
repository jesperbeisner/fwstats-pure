<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use FastRoute;

final class Router
{
    /**
     * @param array<int, array{route: string, methods: string[], controller: string[]}> $routesConfig
     */
    public function __construct(
        private readonly array $routesConfig,
        private readonly Request $request,
    ) {
    }

    /**
     * @return mixed[]
     */
    public function match(): array
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector) {
            foreach ($this->routesConfig as $routeConfig) {
                $routeCollector->addRoute($routeConfig['methods'], $routeConfig['route'], $routeConfig['controller']);
            }
        });

        return $dispatcher->dispatch($this->request->httpMethod, $this->request->uri);
    }
}
