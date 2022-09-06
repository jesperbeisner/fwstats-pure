<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Helper;

final class Length
{
    public static function cut(int $length, string $string): string
    {
        if (strlen($string) > $length) {
            return substr($string, 0, 20);
        }

        return $string;
    }
}
