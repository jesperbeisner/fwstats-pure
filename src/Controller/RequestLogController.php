<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\RequestLogRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class RequestLogController implements ControllerInterface
{
    public function __construct(
        private RequestLogRepository $requestLogRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('request-logs/request-logs.phtml', [
            'requestLogs' => $this->requestLogRepository->findLogsForDay(new DateTimeImmutable()),
        ]);
    }
}
