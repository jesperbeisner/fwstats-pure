<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class LogController implements ControllerInterface
{
    public function __construct(
        private LogRepository $logRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('logs/logs.phtml', [
            'logs' => $this->logRepository->findLast250Logs(),
        ]);
    }
}
