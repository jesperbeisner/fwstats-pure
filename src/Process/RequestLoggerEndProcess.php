<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Interface\EndProcessInterface;
use Jesperbeisner\Fwstats\Repository\RequestLogRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class RequestLoggerEndProcess implements EndProcessInterface
{
    public function __construct(
        private RequestLogRepository $requestLogRepository,
    ) {
    }

    public function run(Request $request, Response $response): void
    {
        $this->requestLogRepository->log($request->getRequestUri(), $request->getHttpMethod(), $response->statusCode, new DateTimeImmutable());
    }
}
