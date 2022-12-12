<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Middleware;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Log;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final readonly class DatabaseRequestLoggerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Request $request,
        private LogRepository $logRepository,
    ) {
    }

    public function run(): ?ResponseInterface
    {
        $this->logRepository->insert(new Log($this->request->getRequestUri(), new DateTimeImmutable()));

        return null;
    }
}
