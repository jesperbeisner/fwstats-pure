<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;

interface FreewarDumpServiceInterface
{
    /**
     * @return array<Player>
     */
    public function getPlayersDump(WorldEnum $world): array;

    /**
     * @return array<Clan>
     */
    public function getClansDump(WorldEnum $world): array;

    /**
     * @return array<int, array<int, int>>
     */
    public function getAchievementsDump(WorldEnum $world): array;
}
