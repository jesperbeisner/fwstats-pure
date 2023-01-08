<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use Jesperbeisner\Fwstats\Interface\ProcessInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final readonly class SessionProcess implements ProcessInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {
    }

    public function run(Request $request): void
    {
        $this->session->start();
    }
}
