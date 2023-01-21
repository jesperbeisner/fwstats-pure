<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

interface EndProcessInterface
{
    public function run(Request $request, Response $response): void;
}
