<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class Achievement
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public int $fieldsWalked,
        public int $fieldsElixir,
        public int $fieldsRun,
        public int $fieldsRunFast,
        public int $npcKillsGold,
        public int $normalNpcKilled,
        public int $phaseNpcKilled,
        public int $aggressiveNpcKilled,
        public int $invasionNpcKilled,
        public int $uniqueNpcKilled,
        public int $groupNpcKilled,
        public int $soulStonesGained,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, Achievement $achievement): Achievement
    {
        return new Achievement(
            $id,
            $achievement->world,
            $achievement->playerId,
            $achievement->fieldsWalked,
            $achievement->fieldsElixir,
            $achievement->fieldsRun,
            $achievement->fieldsRunFast,
            $achievement->npcKillsGold,
            $achievement->normalNpcKilled,
            $achievement->phaseNpcKilled,
            $achievement->aggressiveNpcKilled,
            $achievement->invasionNpcKilled,
            $achievement->uniqueNpcKilled,
            $achievement->groupNpcKilled,
            $achievement->soulStonesGained,
            $achievement->created
        );
    }
}
