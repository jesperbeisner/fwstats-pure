<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Interface\ProcessInterface;
use Jesperbeisner\Fwstats\Model\Log;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;

final readonly class RequestLoggerProcess implements ProcessInterface
{
    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function run(Request $request): void
    {
        $this->logRepository->insert(new Log($request->getRequestUri(), new DateTimeImmutable()));
    }
}
