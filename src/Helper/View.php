<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Helper;

use Jesperbeisner\Fwstats\DTO\Player;

final class View
{
    public static function escape(string $text): string
    {
        return htmlspecialchars($text);
    }

    public static function numberFormat(int $number): string
    {
        return number_format((float) $number, 0, '', '.');
    }

    public static function shortNames(string $name): string
    {
        if (strlen($name) > 20) {
            return substr($name, 0, 20) . '...';
        }

        return $name;
    }

    public static function calculateSoulLevel(Player $player): ?int
    {
        if ($player->xp !== 200_000) {
            return null;
        }

        switch ($player->soulXp) {
            case $player->soulXp < 50_000:
                return 0;
            case $player->soulXp < 50_000 + 100_000:
                return 1;
            case $player->soulXp < 50_000 + 100_000 + 200_000:
                return 2;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000:
                return 3;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000:
                return 4;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000:
                return 5;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000:
                return 6;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000:
                return 7;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000:
                return 8;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000:
                return 9;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000:
                return 10;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000:
                return 11;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000 + 1_200_000:
                return 12;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000 + 1_200_000 + 1_300_000:
                return 13;
            case $player->soulXp < 50_000 + 100_000 + 200_000 + 300_000 + 400_000 + 500_000 + 600_000 + 700_000 + 800_000 + 900_000 + 1_000_000 + 1_100_000 + 1_200_000 + 1_300_000 + 1_400_000:
                return 14;
            default:
                return 100;
        }
    }
}
