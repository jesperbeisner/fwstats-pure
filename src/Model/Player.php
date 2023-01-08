<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\PlayerInterface;
use RuntimeException;

final readonly class Player implements PlayerInterface
{
    public function __construct(
        public WorldEnum $world,
        public int $playerId,
        public string $name,
        public string $race,
        public int $xp,
        public int $soulXp,
        public int $totalXp,
        public ?int $clanId,
        public ?string $profession,
        public DateTimeImmutable $created,
    ) {
    }

    public function getWorld(): WorldEnum
    {
        return $this->world;
    }

    public function getPlayerId(): int
    {
        return $this->playerId;
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

    /*
     * You can certainly make it easier. I do not know... But it works.
     */
    public function getSoulLevel(): ?int
    {
        if ($this->xp !== 200_000) {
            return null;
        }

        if ($this->soulXp < 50_000) {
            return 0;
        }

        if ($this->soulXp < 150_000) {
            return 1;
        }

        if ($this->soulXp < 350_000) {
            return 2;
        }

        if ($this->soulXp < 650_000) {
            return 3;
        }

        if ($this->soulXp < 1_050_000) {
            return 4;
        }

        if ($this->soulXp < 1_550_000) {
            return 5;
        }

        if ($this->soulXp < 2_150_000) {
            return 6;
        }

        if ($this->soulXp < 2_850_000) {
            return 7;
        }

        if ($this->soulXp < 3_650_000) {
            return 8;
        }

        if ($this->soulXp < 4_550_000) {
            return 9;
        }

        if ($this->soulXp < 5_550_000) {
            return 10;
        }

        if ($this->soulXp < 6_650_000) {
            return 11;
        }

        if ($this->soulXp < 7_850_000) {
            return 12;
        }

        if ($this->soulXp < 9_150_000) {
            return 13;
        }

        if ($this->soulXp < 10_550_000) {
            return 14;
        }

        if ($this->soulXp < 12_050_000) {
            return 15;
        }

        return 100;
    }
}
