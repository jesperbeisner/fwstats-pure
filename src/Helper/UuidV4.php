<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Helper;

final class UuidV4
{
    public static function create(): string
    {
        $uuid = random_bytes(16);
        $uuid[6] = $uuid[6] & "\x0F" | "\x40";
        $uuid[8] = $uuid[8] & "\x3F" | "\x80";
        $uuid = bin2hex($uuid);

        return substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' . substr($uuid, 16, 4) . '-' . substr($uuid, 20, 12);
    }
}
