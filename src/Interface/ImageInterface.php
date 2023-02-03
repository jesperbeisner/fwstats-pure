<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

interface ImageInterface
{
    public function create(WorldEnum $world): void;
}
