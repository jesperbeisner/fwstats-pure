<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use Jesperbeisner\Fwstats\Interface\StartProcessInterface;
use Jesperbeisner\Fwstats\Interface\RouterInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final readonly class RouterStartProcess implements StartProcessInterface
{
    public function __construct(
        private RouterInterface $router,
    ) {
    }

    public function run(Request $request): void
    {
        $this->router->route($request);
    }
}
