<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Stdlib\Request;

interface RouterInterface
{
    public function route(Request $request): void;
}
