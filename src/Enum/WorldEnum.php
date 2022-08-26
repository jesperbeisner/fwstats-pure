<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Enum;

enum WorldEnum: string
{
    case AFSRV = 'afsrv';
    case CHAOS = 'chaos';

    public function worldString(): string
    {
        return match ($this) {
            WorldEnum::AFSRV => 'ActionFreewar',
            WorldEnum::CHAOS => 'ChaosFreewar',
        };
    }
}
