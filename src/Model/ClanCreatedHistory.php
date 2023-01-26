<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class ClanCreatedHistory
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

    public static function withId(int $id, ClanCreatedHistory $clanCreatedHistory): ClanCreatedHistory
    {
        return new ClanCreatedHistory(
            $id,
            $clanCreatedHistory->world,
            $clanCreatedHistory->clanId,
            $clanCreatedHistory->shortcut,
            $clanCreatedHistory->name,
            $clanCreatedHistory->leaderId,
            $clanCreatedHistory->coLeaderId,
            $clanCreatedHistory->diplomatId,
            $clanCreatedHistory->warPoints,
        );
    }
}
