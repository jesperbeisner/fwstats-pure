<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Doubles;

use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Interface\PlayerStatusServiceInterface;
use Jesperbeisner\Fwstats\Model\Player;

final readonly class PlayerStatusServiceDummy implements PlayerStatusServiceInterface
{
    public function __construct(
        private PlayerStatusEnum $playerStatusEnum,
    ) {
    }

    public function getStatus(Player $player): PlayerStatusEnum
    {
        return $this->playerStatusEnum;
    }
}
