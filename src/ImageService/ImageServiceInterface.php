<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\ImageService;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

interface ImageServiceInterface
{
    public function create(WorldEnum $world): void;
}
