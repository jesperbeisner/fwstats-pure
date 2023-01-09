<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Helper;

use Jesperbeisner\Fwstats\Exception\RuntimeException;

final readonly class Str
{
    public static function random(int $length): string
    {
        if ($length < 1) {
            throw new RuntimeException('The "$length" parameter can not be less than one, "%d" given.', $length);
        }

        $string = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $max = mb_strlen($characters, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $string .= $characters[random_int(0, $max)];
        }

        return $string;
    }
}
