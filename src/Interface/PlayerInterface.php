<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

interface PlayerInterface
{
    public function getPlayerId(): int;

    public function getWorld(): WorldEnum;
}
