<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final readonly class Clan
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
        public DateTimeImmutable $created,
    ) {
    }

    public static function withId(int $id, Clan $clan): Clan
    {
        return new Clan(
            $id,
            $clan->world,
            $clan->clanId,
            $clan->shortcut,
            $clan->name,
            $clan->leaderId,
            $clan->coLeaderId,
            $clan->diplomatId,
            $clan->warPoints,
            $clan->created,
        );
    }
}
