<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use Jesperbeisner\Fwstats\Controller\AbstractController;
use Jesperbeisner\Fwstats\Stdlib\Router;
use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;
use Jesperbeisner\Fwstats\Stdlib\HtmlResponse;
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
        /** @var Router $router */
        $router = $this->serviceContainer->get(Router::class);

        $routeResult = $router->match();

        if ($routeResult[0] === Router::NOT_FOUND) {
            (new HtmlResponse('errors/404.phtml'))->send();
        }

        if ($routeResult[0] === Router::METHOD_NOT_ALLOWED) {
            (new HtmlResponse('errors/405.phtml'))->send();
        }

        /** @var class-string<AbstractController> $controllerClassName */
        $controllerClassName = $routeResult[1];

        /** @var AbstractController $controller */
        $controller = $this->serviceContainer->get($controllerClassName);
        $response = $controller();

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
}
