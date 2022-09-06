<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Repository\LogRepository;
use Jesperbeisner\Fwstats\Stdlib\Exception\UnauthorizedException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class LogController extends AbstractController
{
    public function __construct(
        private readonly SessionInterface $session,
        private readonly LogRepository $logRepository,
    ) {
    }

    public function logs(): ResponseInterface
    {
        if ($this->session->getUser() === null) {
            throw new UnauthorizedException();
        }

        return new HtmlResponse('logs/logs.phtml', [
            'logs' => $this->logRepository->findLast250Logs(),
        ]);
    }
}
