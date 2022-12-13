<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final readonly class LogController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
        private LogRepository $logRepository,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        if ($this->session->getUser() === null) {
            return new HtmlResponse('error.phtml', ['message' => '401 - Unauthorized'], 401);
        }

        return new HtmlResponse('logs/logs.phtml', [
            'logs' => $this->logRepository->findLast250Logs(),
        ]);
    }
}
