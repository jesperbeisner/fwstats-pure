<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class LogsController extends AbstractController
{
    public function __construct(
        private readonly string $logsPassword,
        private readonly Request $request,
        private readonly LogRepository $logRepository,
    ) {
    }

    public function logs(): ResponseInterface
    {
        $password = $this->request->getGetParameter('password');
        if ($password === null || $password !== $this->logsPassword) {
            $this->unauthorizedException();
        }

        return new HtmlResponse('logs/logs.phtml', [
            'logs' => $this->logRepository->findLast250Logs(),
        ]);
    }
}
