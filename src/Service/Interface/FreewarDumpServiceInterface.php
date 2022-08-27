<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Interface;

use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

interface FreewarDumpServiceInterface
{
    /**
     * @return Player[]
     */
    public function getPlayersDump(WorldEnum $world): array;

    /**
     * @return Clan[]
     */
    public function getClansDump(WorldEnum $world): array;

    /**
     * @return array<int, array<int, int>>
     */
    public function getAchievementsDump(WorldEnum $world): array;
}
