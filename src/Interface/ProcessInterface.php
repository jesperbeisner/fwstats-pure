<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Stdlib\Request;

interface ProcessInterface
{
    public function run(Request $request): void;
}
