<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Middleware;

use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Interface\LoggerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Throwable;

final readonly class ExceptionHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private Config $config,
    ) {
    }

    public function run(): ?ResponseInterface
    {
        set_exception_handler(function (Throwable $e): never {
            $this->logger->error($e->getMessage());

            if ($this->config->getAppEnv() === 'prod') {
                (new HtmlResponse('error.phtml', ['message' => '500 - Server error'], 500))->send();
            }

            throw $e;
        });

        return null;
    }
}
