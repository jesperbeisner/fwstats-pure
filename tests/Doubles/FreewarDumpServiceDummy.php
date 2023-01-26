<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Doubles;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Model\Player;

final readonly class FreewarDumpServiceDummy implements FreewarDumpServiceInterface
{
    /**
     * @param array<Player> $playersDump
     * @param array<Clan> $clansDump
     * @param array<int, array<int, int>> $achievementsDump
     */
    public function __construct(
        private array $playersDump = [],
        private array $clansDump = [],
        private array $achievementsDump = [],
    ) {
    }

    public function getPlayersDump(WorldEnum $world): array
    {
        return $this->playersDump;
    }

    public function getClansDump(WorldEnum $world): array
    {
        return $this->clansDump;
    }

    public function getAchievementsDump(WorldEnum $world): array
    {
        return $this->achievementsDump;
    }
}
