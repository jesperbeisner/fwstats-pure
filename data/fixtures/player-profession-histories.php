<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Enum\WorldEnum;

return [
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldProfession' => null,
        'newProfession' => 'Alchemist',
        'created' => new DateTimeImmutable('-3 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 1,
        'oldProfession' => 'Alchemist',
        'newProfession' => 'Sammler',
        'created' => new DateTimeImmutable('-1 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 2,
        'oldProfession' => null,
        'newProfession' => 'Magieverlängerer',
        'created' => new DateTimeImmutable('-7 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 2,
        'oldProfession' => 'Magieverlängerer',
        'newProfession' => 'Schatzmeister',
        'created' => new DateTimeImmutable('-6 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 3,
        'oldProfession' => null,
        'newProfession' => 'Schatzmeister',
        'created' => new DateTimeImmutable('-5 days'),
    ],
    [
        'world' => WorldEnum::AFSRV,
        'playerId' => 3,
        'oldProfession' => 'Schatzmeister',
        'newProfession' => 'Magieverlängerer',
        'created' => new DateTimeImmutable('-4 days'),
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 1,
        'oldProfession' => null,
        'newProfession' => 'Magieverlängerer',
        'created' => new DateTimeImmutable('-7 days'),
    ],
    [
        'world' => WorldEnum::CHAOS,
        'playerId' => 1,
        'oldProfession' => 'Magieverlängerer',
        'newProfession' => 'Schatzmeister',
        'created' => new DateTimeImmutable('-3 days'),
    ],
];
