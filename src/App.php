<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use FastRoute\Dispatcher;
use Jesperbeisner\Fwstats\Middleware\MiddlewareInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\RouterInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class App
{
    /**
     * @param ContainerInterface $container
     * @param array<class-string<MiddlewareInterface>> $middlewares
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly array $middlewares,
    ) {
    }

    public function handle(Request $request): ResponseInterface
    {
        $this->container->set(Request::class, $request);

        foreach ($this->middlewares as $middlewareName) {
            /** @var MiddlewareInterface $middleware */
            $middleware = $this->container->get($middlewareName);

            $response = $middleware->run();

            if ($response instanceof ResponseInterface) {
                return $response;
            }
        }

        /** @var RouterInterface $router */
        $router = $this->container->get(RouterInterface::class);

        $routeResult = $router->match($request);

        if ($routeResult[0] === Dispatcher::NOT_FOUND) {
            $response = new HtmlResponse('error.phtml', ['message' => '404 - Page not found'], 404);
        } elseif ($routeResult[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            $response = new HtmlResponse('error.phtml', ['message' => '405 - Method not allowed'], 405);
        } else {
            /** @var array<string, string> $routeParameters */
            $routeParameters = $routeResult[2];
            $request->setRouteParameters($routeParameters);

            /** @var class-string<ControllerInterface> $controllerName */
            $controllerName = $routeResult[1];

            /** @var ControllerInterface $controller */
            $controller = $this->container->get($controllerName);

            $response = $controller();
        }

        if ($response instanceof HtmlResponse) {
            /** @var SessionInterface $session */
            $session = $this->container->get(SessionInterface::class);

            $response->setSession($session);
        }

        return $response;
    }
}
