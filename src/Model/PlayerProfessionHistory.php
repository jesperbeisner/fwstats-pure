<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class PlayerProfessionHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $playerId,
        public ?string $oldProfession,
        public ?string $newProfession,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, PlayerProfessionHistory $playerProfessionHistory): PlayerProfessionHistory
    {
        return new PlayerProfessionHistory(
            $id,
            $playerProfessionHistory->world,
            $playerProfessionHistory->playerId,
            $playerProfessionHistory->oldProfession,
            $playerProfessionHistory->newProfession,
            $playerProfessionHistory->created,
        );
    }
}
