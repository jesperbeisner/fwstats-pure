<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\DTO;

use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class Playtime
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly string $name,
        public readonly int $playerId,
        public readonly int $playtime,
    ) {
    }

    public function getPlaytime(): string
    {
        // TODO: Muss überarbeitet werden, nur hingeschmiert...

        if ($this->playtime < 60) {
            return "$this->playtime sec";
        }

        if ($this->playtime < 3600) {
            $minutes = (string) floor($this->playtime / 60);
            $seconds = (string) ($this->playtime % 60);

            return "$minutes min & $seconds sec";
        }

        $playtime = $this->playtime;

        $hours = (string) floor($playtime / 60 / 60);
        $playtime = $playtime - ($hours * 60 * 60);

        $minutes = (string) floor($playtime / 60);
        $seconds = (string) ($this->playtime % 60);

        return "$hours h & $minutes min & $seconds sec";
    }
}
