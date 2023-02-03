<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\RequestLogRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\RequestLogControllerTest
 */
#[LoginRequired]
final readonly class RequestLogController implements ControllerInterface
{
    public function __construct(
        private RequestLogRepository $requestLogRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        if (null === $dayString = $request->getGetParameter('day')) {
            $day = new DateTimeImmutable();
        } else {
            try {
                $day = new DateTimeImmutable($dayString);
            } catch (Exception) {
                $day = new DateTimeImmutable();
            }
        }

        return Response::html('request-logs/request-logs.phtml', [
            'day' => $day,
            'requestLogs' => $this->requestLogRepository->findLogsForDay($day),
        ]);
    }
}
