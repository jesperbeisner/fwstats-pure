<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

interface ImporterInterface
{
    public function import(WorldEnum $world): ImportResult;
}
