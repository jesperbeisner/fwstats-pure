<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use FastRoute;

final class Router
{
    public const NOT_FOUND = 0;
    public const FOUND = 1;
    public const METHOD_NOT_ALLOWED = 2;

    public function __construct(
        private readonly array $routesConfig,
        private readonly Request $request,
    ) {
    }

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
