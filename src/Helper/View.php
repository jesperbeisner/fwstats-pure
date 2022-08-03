<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Helper;

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
        if (strlen($name) < 16) {
            return $name;
        }

        return substr($name, 0, 15) . '...';
    }
}
