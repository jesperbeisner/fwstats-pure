<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Enum;

enum PlayerStatusEnum: string
{
    case BANNED = 'banned';
    case DELETED = 'deleted';
    case UNKNOWN = 'unknown';
}
