<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use Jesperbeisner\Fwstats\Interface\ProcessInterface;
use Jesperbeisner\Fwstats\Service\SetupService;
use Jesperbeisner\Fwstats\Stdlib\Request;

final readonly class SetupProcess implements ProcessInterface
{
    public function __construct(
        private SetupService $setupService,
    ) {
    }

    public function run(Request $request): void
    {
        $this->setupService->setup();
    }
}
