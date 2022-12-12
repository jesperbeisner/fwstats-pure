<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use DateTimeImmutable;
use FastRoute\Dispatcher;
use Jesperbeisner\Fwstats\Controller\AbstractController;
use Jesperbeisner\Fwstats\Middleware\MiddlewareInterface;
use Jesperbeisner\Fwstats\Model\Log;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Exception\RedirectException;
use Jesperbeisner\Fwstats\Stdlib\Exception\UnauthorizedException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\LoggerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Response\RedirectResponse;
use Jesperbeisner\Fwstats\Stdlib\Router;
use Throwable;

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

        /** @var SessionInterface $session */
        $session = $this->container->get(SessionInterface::class);
        $session->start();

        /** @var Router $router */
        $router = $this->container->get(Router::class);
        $routeResult = $router->match($request);

        if ($routeResult[0] === Dispatcher::NOT_FOUND) {
            $response = new HtmlResponse('error.phtml', ['message' => '404 - Page not found'], 404);
            $response->setSession($session);

            $response->send();
        }

        if ($routeResult[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            $response = new HtmlResponse('error.phtml', ['message' => '405 - Method not allowed'], 405);
            $response->setSession($session);

            $response->send();
        }

        /** @var array<string, string> $routeParameters */
        $routeParameters = $routeResult[2];
        $request->setRouteParameters($routeParameters);

        /** @var array{class-string<AbstractController>, string} $handler */
        $handler = $routeResult[1];

        $controllerClassName = $handler[0];
        $controllerAction = $handler[1];

        /** @var AbstractController $controller */
        $controller = $this->container->get($controllerClassName);

        try {
            /** @var ResponseInterface $response */
            $response = $controller->$controllerAction(); // @phpstan-ignore-line
        } catch (NotFoundException | UnauthorizedException | RedirectException $e) {
            if ($e instanceof NotFoundException) {
                $response = new HtmlResponse('error.phtml', ['message' => $e->getMessage()], 404);
                $response->setSession($session);

                return $response;
            }

            if ($e instanceof UnauthorizedException) {
                $response = new HtmlResponse('error.phtml', ['message' => $e->getMessage()], 401);
                $response->setSession($session);

                return $response;
            }

            return new RedirectResponse($e->route);
        }

        if ($response instanceof HtmlResponse) {
            $response->setSession($session);
        }

        return $response;
    }
}
