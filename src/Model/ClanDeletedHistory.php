<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class ClanDeletedHistory
{
    public function __construct(
        public ?int $id,
        public WorldEnum $world,
        public int $clanId,
        public string $shortcut,
        public string $name,
        public int $leaderId,
        public int $coLeaderId,
        public int $diplomatId,
        public int $warPoints,
    ) {
    }

    public static function withId(int $id, ClanDeletedHistory $clanDeletedHistory): ClanDeletedHistory
    {
        return new ClanDeletedHistory(
            $id,
            $clanDeletedHistory->world,
            $clanDeletedHistory->clanId,
            $clanDeletedHistory->shortcut,
            $clanDeletedHistory->name,
            $clanDeletedHistory->leaderId,
            $clanDeletedHistory->coLeaderId,
            $clanDeletedHistory->diplomatId,
            $clanDeletedHistory->warPoints,
        );
    }
}
