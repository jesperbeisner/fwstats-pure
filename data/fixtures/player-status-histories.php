<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

$created1 = new DateTimeImmutable('-1 day');
$created2 = new DateTimeImmutable('-2 day');
$created3 = new DateTimeImmutable('-3 day');
$created4 = new DateTimeImmutable('-4 day');
$created5 = new DateTimeImmutable('-5 day');

return [
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'name' => 'Test-1',
        'status' => PlayerStatusEnum::BANNED,
        'created' => $created1,
        'deleted' => null,
        'updated' => $created1,
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 2,
        'name' => 'Test-2',
        'status' => PlayerStatusEnum::BANNED,
        'created' => $created2,
        'deleted' => null,
        'updated' => $created2,
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 3,
        'name' => 'Test-3',
        'status' => PlayerStatusEnum::DELETED,
        'created' => $created3,
        'deleted' => new DateTimeImmutable(),
        'updated' => $created3,
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 4,
        'name' => 'PlayerWithAReallyLongName',
        'status' => PlayerStatusEnum::DELETED,
        'created' => $created4,
        'deleted' => new DateTimeImmutable('-1 day'),
        'updated' => $created4,
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 5,
        'name' => 'Hello World',
        'status' => PlayerStatusEnum::BANNED,
        'created' => $created5,
        'deleted' => new DateTimeImmutable('-3 days'),
        'updated' => $created5,
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 1,
        'name' => 'Test-1',
        'status' => PlayerStatusEnum::BANNED,
        'created' => $created1,
        'deleted' => null,
        'updated' => $created1,
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 2,
        'name' => 'Test-2',
        'status' => PlayerStatusEnum::BANNED,
        'created' => $created2,
        'deleted' => null,
        'updated' => $created2,
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 3,
        'name' => 'Test-3',
        'status' => PlayerStatusEnum::DELETED,
        'created' => $created3,
        'deleted' => new DateTimeImmutable(),
        'updated' => $created3,
    ],
];
