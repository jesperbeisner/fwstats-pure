<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Enum\WorldEnum;

$playerPlaytimes = [];

for ($i = 1; $i <= 5; $i++) {
    $created = new DateTime('+1 day');
    $playtime = 1_000_000;

    for ($j = 0; $j < 10; $j++) {
        $hours = rand(1, 5) * 60 * 60;
        $minutes = rand(1, 59) * 60;
        $seconds = rand (1, 59);

        $playtime = $playtime - ($hours + $minutes + $seconds);

        $playerPlaytimes[] = [
            'world' => WorldEnum::AFSRV,
            'playerId' => $i,
            'seconds' => $playtime,
            'created' => $created->modify('-1 day')->format('Y-m-d'),
        ];
    }
}

for ($i = 1; $i <= 3; $i++) {
    $created = new DateTime('+1 day');
    $playtime = 1_000_000;

    for ($j = 0; $j < 10; $j++) {
        $hours = rand(1, 5) * 60 * 60;
        $minutes = rand(1, 59) * 60;
        $seconds = rand (1, 59);

        $playtime = $playtime - ($hours + $minutes + $seconds);

        $playerPlaytimes[] = [
            'world' => WorldEnum::CHAOS,
            'playerId' => $i,
            'seconds' => $playtime,
            'created' => $created->modify('-1 day')->format('Y-m-d'),
        ];
    }
}

return $playerPlaytimes;
