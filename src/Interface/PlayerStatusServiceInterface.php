<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Model\Player;

interface PlayerStatusServiceInterface
{
    public function getStatus(Player $player): PlayerStatusEnum;
}
