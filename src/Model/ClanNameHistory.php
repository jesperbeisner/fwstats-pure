<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class ClanNameHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $clanId,
        public string $oldShortcut,
        public string $newShortcut,
        public string $oldName,
        public string $newName,
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, ClanNameHistory $clanNameHistory): ClanNameHistory
    {
        return new ClanNameHistory(
            $id,
            $clanNameHistory->world,
            $clanNameHistory->clanId,
            $clanNameHistory->oldShortcut,
            $clanNameHistory->newShortcut,
            $clanNameHistory->oldName,
            $clanNameHistory->newName,
            $clanNameHistory->created,
        );
    }
}
