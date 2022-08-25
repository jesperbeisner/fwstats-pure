<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use FastRoute\Dispatcher;
use Jesperbeisner\Fwstats\Controller\AbstractController;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Router;
use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;
use Psr\Log\LoggerInterface;
use Throwable;

final class App
{
    public function __construct(
        private readonly ServiceContainer $serviceContainer,
    ) {
        $this->registerExceptionHandler();
    }

    public function run(): never
    {
        $this->logRequest();

        /** @var Router $router */
        $router = $this->serviceContainer->get(Router::class);

        $routeResult = $router->match();

        if ($routeResult[0] === Dispatcher::NOT_FOUND) {
            (new HtmlResponse('errors/404.phtml'))->send();
        }

        if ($routeResult[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            (new HtmlResponse('errors/405.phtml'))->send();
        }

        /** @var Request $request */
        $request = $this->serviceContainer->get(Request::class);

        /** @var array<string, string> $routeParameters */
        $routeParameters = $routeResult[2];
        $request->setRouteParameters($routeParameters);

        /** @var array{class-string<AbstractController>, string} $handler */
        $handler = $routeResult[1];

        $controllerClassName = $handler[0];
        $controllerAction = $handler[1];

        /** @var AbstractController $controller */
        $controller = $this->serviceContainer->get($controllerClassName);

        try {
            /** @var ResponseInterface $response */
            $response = $controller->$controllerAction();
        } catch (NotFoundException $e) {
            (new HtmlResponse('errors/404.phtml', ['message' => $e->getMessage()]))->send();
        }

        $response->send();
    }

    private function registerExceptionHandler(): void
    {
        set_exception_handler(function (Throwable $e): never {
            /** @var string $appEnv */
            $appEnv = $this->serviceContainer->get('appEnv');

            if ($appEnv === 'prod') {
                (new HtmlResponse('errors/500.phtml'))->send();
            }

            throw $e;
        });
    }

    private function logRequest(): void
    {
        /** @var Request $request */
        $request = $this->serviceContainer->get(Request::class);

        /** @var LoggerInterface $logger */
        $logger = $this->serviceContainer->get(LoggerInterface::class);

        $logger->info($request->fullUri);
    }
}
