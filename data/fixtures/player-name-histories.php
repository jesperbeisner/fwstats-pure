<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Enum\WorldEnum;

return [
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldName' => 'Test-1',
        'newName' => 'Test-2',
        'created' => new DateTimeImmutable('-7 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldName' => 'Test-2',
        'newName' => 'Test-3',
        'created' => new DateTimeImmutable('-3 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldName' => 'Test-3',
        'newName' => 'TarX',
        'created' => new DateTimeImmutable(),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 2,
        'oldName' => 'Kuseng',
        'newName' => 'Pomsky',
        'created' => new DateTimeImmutable('-1 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 3,
        'oldName' => 'Boring',
        'newName' => 'Weißes Haus',
        'created' => new DateTimeImmutable('-5 days'),
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 1,
        'oldName' => 'Xartos',
        'newName' => 'Sotrax',
        'created' => new DateTimeImmutable('-1 days'),
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 2,
        'oldName' => 'bob',
        'newName' => 'bwoebi',
        'created' => new DateTimeImmutable('-7 days'),
    ],
];
