<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

interface ImageServiceInterface
{
    public function create(WorldEnum $world): void;
}
