<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use RuntimeException;

final class Player
{
    public function __construct(
        public readonly WorldEnum $world,
        public readonly int $playerId,
        public readonly string $name,
        public readonly string $race,
        public readonly int $xp,
        public readonly int $soulXp,
        public readonly int $totalXp,
        public readonly ?int $clanId,
        public readonly ?string $profession,
    ) {
    }

    public function getRaceShortcut(): string
    {
        return match ($this->race) {
            'Onlo' => 'Onlo',
            'Natla - Händler' => 'Natla',
            'Mensch / Kämpfer' => 'M/K',
            'Mensch / Zauberer' => 'M/Z',
            'Mensch / Arbeiter' => 'M/A',
            'Keuroner' => 'Keuroner',
            'Taruner' => 'Taruner',
            'Serum-Geist' => 'Serum',
            'dunkler Magier' => 'D/M',
            default => throw new RuntimeException("New race in freewar? o.O")
        };
    }

    public function getSoulLevel(): ?int
    {
        if ($this->xp !== 200_000) {
            return null;
        }

        switch ($this->soulXp) {
            case $this->soulXp < 50_000:
                return 0;
            case $this->soulXp < 50_000 + 100_000:
                return 1;
            case $this->soulXp < 50_000 + 100_000 + 200_000:
                return 2;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000:
                return 3;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000:
                return 4;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000:
                return 5;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000:
                return 6;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000:
                return 7;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000:
                return 8;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000:
                return 9;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000:
                return 10;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000:
                return 11;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000 + 1_200_000:
                return 12;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000 + 1_200_000 + 1_300_000:
                return 13;
            case $this->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000 + 1_200_000 + 1_300_000 + 1_400_000:
                return 14;
            default:
                return 100;
        }
    }
}
