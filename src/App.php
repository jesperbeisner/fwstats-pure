<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use DateTimeImmutable;
use FastRoute\Dispatcher;
use Jesperbeisner\Fwstats\Controller\AbstractController;
use Jesperbeisner\Fwstats\Model\Log;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Exception\UnauthorizedException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Router;
use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;
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
        $this->logRequestToDatabase();

        /** @var Router $router */
        $router = $this->serviceContainer->get(Router::class);

        $routeResult = $router->match();

        if ($routeResult[0] === Dispatcher::NOT_FOUND) {
            (new HtmlResponse('error.phtml', ['message' => '404 - Page not found'], 404))->send();
        }

        if ($routeResult[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            (new HtmlResponse('error.phtml', ['message' => '405 - Method not allowed'], 405))->send();
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
            (new HtmlResponse('error.phtml', ['message' => $e->getMessage()], 404))->send();
        } catch (UnauthorizedException $e) {
            (new HtmlResponse('error.phtml', ['message' => $e->getMessage()], 401))->send();
        }

        $response->send();
    }

    private function registerExceptionHandler(): void
    {
        set_exception_handler(function (Throwable $e): never {
            /** @var string $appEnv */
            $appEnv = $this->serviceContainer->get('appEnv');

            if ($appEnv === 'prod') {
                (new HtmlResponse('error.phtml', ['message' => '500 - Server error'], 500))->send();
            }

            throw $e;
        });
    }

    private function logRequestToDatabase(): void
    {
        /** @var Request $request */
        $request = $this->serviceContainer->get(Request::class);

        /** @var LogRepository $logRepository */
        $logRepository = $this->serviceContainer->get(LogRepository::class);

        $log = new Log($request->fullUri, new DateTimeImmutable());

        $logRepository->insert($log);
    }
}
