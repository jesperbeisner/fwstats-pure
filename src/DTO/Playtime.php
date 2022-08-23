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

    public function getHours(): int
    {
        if ($this->playtime < 3600) {
            return 0;
        }

        return (int) floor($this->playtime / 60 / 60);
    }

    public function getMinutes(): int
    {
        if ($this->playtime < 60) {
            return 0;
        }

        $minutes = (int) floor($this->playtime / 60);

        if ($minutes >= 60) {
            return $minutes % 60;
        }

        return $minutes;
    }

    public function getSeconds(): int
    {
        $playtime = $this->playtime;

        if ($playtime >= 3600) {
            $hours = (int) floor($playtime / 60 / 60);
            $playtime = $playtime - ($hours * 60 * 60);
        }

        if ($playtime >= 60) {
            $minutes = (int) floor($playtime / 60);
            $playtime = $playtime - ($minutes * 60);
        }

        return $playtime;
    }
}
