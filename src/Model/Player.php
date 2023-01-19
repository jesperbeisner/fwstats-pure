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
        public ?int $id,
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

    public static function withId(int $id, Player $player): Player
    {
        return new Player($id, $player->world, $player->playerId, $player->name, $player->race, $player->xp, $player->soulXp, $player->totalXp, $player->clanId, $player->profession, $player->created);
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
    public static function getSoulLevel(int $xp, int $soulXp): ?int
    {
        if ($xp !== 200_000) {
            return null;
        }

        if ($soulXp < 50_000) {
            return 0;
        }

        if ($soulXp < 150_000) {
            return 1;
        }

        if ($soulXp < 350_000) {
            return 2;
        }

        if ($soulXp < 650_000) {
            return 3;
        }

        if ($soulXp < 1_050_000) {
            return 4;
        }

        if ($soulXp < 1_550_000) {
            return 5;
        }

        if ($soulXp < 2_150_000) {
            return 6;
        }

        if ($soulXp < 2_850_000) {
            return 7;
        }

        if ($soulXp < 3_650_000) {
            return 8;
        }

        if ($soulXp < 4_550_000) {
            return 9;
        }

        if ($soulXp < 5_550_000) {
            return 10;
        }

        if ($soulXp < 6_650_000) {
            return 11;
        }

        if ($soulXp < 7_850_000) {
            return 12;
        }

        if ($soulXp < 9_150_000) {
            return 13;
        }

        if ($soulXp < 10_550_000) {
            return 14;
        }

        if ($soulXp < 12_050_000) {
            return 15;
        }

        return 100;
    }
}
