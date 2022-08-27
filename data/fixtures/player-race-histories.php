<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Enum\WorldEnum;

return [
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldRace' => 'Serum-Geist',
        'newRace' => 'Mensch / Zauberer',
        'created' => new DateTimeImmutable('-2 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldRace' => 'Mensch / Zauberer',
        'newRace' => 'Taruner',
        'created' => new DateTimeImmutable('-1 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldRace' => 'Taruner',
        'newRace' => 'Onlo',
        'created' => new DateTimeImmutable(),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 2,
        'oldRace' => 'Onlo',
        'newRace' => 'Serum-Geist',
        'created' => new DateTimeImmutable('-7 days'),
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 1,
        'oldRace' => 'Onlo',
        'newRace' => 'Taruner',
        'created' => new DateTimeImmutable('-3 days'),
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 1,
        'oldRace' => 'Taruner',
        'newRace' => 'Natla - Händler',
        'created' => new DateTimeImmutable('-1 days'),
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 2,
        'oldRace' => 'Taruner',
        'newRace' => 'Mensch / Kämpfer',
        'created' => new DateTimeImmutable('-7 days'),
    ],
];
