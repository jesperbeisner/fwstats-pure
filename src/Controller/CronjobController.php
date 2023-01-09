<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Attribute\TokenRequired;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Service\CronjobService;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[TokenRequired]
final readonly class CronjobController implements ControllerInterface
{
    public function __construct(
        private CronjobService $cronjobService,
    ) {
    }

    public function execute(Request $request): Response
    {
        if ($this->cronjobService->isAllowedToRun() === false) {
            return Response::json(['success' => 'Request received but cronjob is currently not allowed to run.']);
        }

        $this->cronjobService->run();

        return Response::json(['success' => 'Cronjob successfully executed.']);
    }
}
