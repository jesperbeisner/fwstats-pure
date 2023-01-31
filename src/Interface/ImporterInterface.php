<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

interface ImporterInterface
{
    public function import(WorldEnum $world): void;
}
