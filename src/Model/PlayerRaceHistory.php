<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerRaceHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public string $oldRace,
        public string $newRace,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, PlayerRaceHistory $playerRaceHistory): PlayerRaceHistory
    {
        return new PlayerRaceHistory(
            $id,
            $playerRaceHistory->world,
            $playerRaceHistory->playerId,
            $playerRaceHistory->oldRace,
            $playerRaceHistory->newRace,
            $playerRaceHistory->created,
        );
    }
}
