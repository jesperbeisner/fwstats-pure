<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

use Jesperbeisner\Fwstats\Stdlib\Request;

interface RouterInterface
{
    public function match(Request $request): array;
}
