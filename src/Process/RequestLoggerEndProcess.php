<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Interface\EndProcessInterface;
use Jesperbeisner\Fwstats\Model\Log;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class RequestLoggerEndProcess implements EndProcessInterface
{
    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function run(Request $request, Response $response): void
    {
        $this->logRepository->insert(new Log($request->getRequestUri(), new DateTimeImmutable()));
    }
}
