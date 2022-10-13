<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats;

use DateTimeImmutable;
use FastRoute\Dispatcher;
use Jesperbeisner\Fwstats\Controller\AbstractController;
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
use Jesperbeisner\Fwstats\Stdlib\Router;
use Throwable;

final class App
{
    public function __construct(
        private readonly ContainerInterface $container,
    ) {
        $this->registerExceptionHandler();
    }

    public function run(): never
    {
        /** @var Request $request */
        $request = $this->container->get(Request::class);

        $this->logRequestToDatabase($request);

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

        /** @var Request $request */
        $request = $this->container->get(Request::class);

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
            $response = $controller->$controllerAction();
        } catch (NotFoundException | UnauthorizedException | RedirectException $e) {
            if ($e instanceof NotFoundException) {
                $response = new HtmlResponse('error.phtml', ['message' => $e->getMessage()], 404);
                $response->setSession($session);

                $response->send();
            }

            if ($e instanceof UnauthorizedException) {
                $response = new HtmlResponse('error.phtml', ['message' => $e->getMessage()], 401);
                $response->setSession($session);

                $response->send();
            }

            header(header: "Location: $e->route", response_code: 302);
            exit(0);
        }

        if ($response instanceof HtmlResponse) {
            $response->setSession($session);
        }

        $response->send();
    }

    private function registerExceptionHandler(): void
    {
        set_exception_handler(function (Throwable $e): never {
            /** @var Config $config */
            $config = $this->container->get(Config::class);

            /** @var LoggerInterface $logger */
            $logger = $this->container->get(LoggerInterface::class);

            $logger->error($e->getMessage());

            if ($config->getAppEnv() === 'prod') {
                (new HtmlResponse('error.phtml', ['message' => '500 - Server error'], 500))->send();
            }

            throw $e;
        });
    }

    private function logRequestToDatabase(Request $request): void
    {
        /** @var LogRepository $logRepository */
        $logRepository = $this->container->get(LogRepository::class);

        $log = new Log($request->fullUri, new DateTimeImmutable());

        $logRepository->insert($log);
    }
}
