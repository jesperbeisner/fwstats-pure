<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Attribute\TokenRequired;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\CronjobInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\CronjobControllerTest
 */
#[TokenRequired]
final readonly class CronjobController implements ControllerInterface
{
    public function __construct(
        private CronjobInterface $cronjobService,
    ) {
    }

    public function execute(Request $request): Response
    {
        if ($this->cronjobService->isAllowedToRun() === false) {
            return Response::json(['Success' => 'Request received but cronjob is currently not allowed to run.']);
        }

        $this->cronjobService->run();

        return Response::json(['Success' => 'Cronjob successfully executed.']);
    }
}
