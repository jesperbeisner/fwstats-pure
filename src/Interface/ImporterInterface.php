<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Result\ImportResult;

interface ImporterInterface
{
    public function import(WorldEnum $world): ImportResult;
}
