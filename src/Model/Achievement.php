<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class Achievement
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $playerId,
        public readonly int $fieldsWalked,
        public readonly int $fieldsElixir,
        public readonly int $fieldsRun,
        public readonly int $fieldsRunFast,
        public readonly int $npcKillsGold,
        public readonly int $normalNpcKilled,
        public readonly int $phaseNpcKilled,
        public readonly int $aggressiveNpcKilled,
        public readonly int $invasionNpcKilled,
        public readonly int $uniqueNpcKilled,
        public readonly int $groupNpcKilled,
        public readonly int $soulStonesGained,
    ) {
    }
}
