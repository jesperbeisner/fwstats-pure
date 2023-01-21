<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Stdlib\Request;

interface StartProcessInterface
{
    public function run(Request $request): void;
}
