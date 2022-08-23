<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Controller;

return [
    [
        'route' => '/',
        'methods' => ['GET'],
        'controller' => [Controller\IndexController::class, 'index'],
    ],
    [
        'route' => '/playtime',
        'methods' => ['GET'],
        'controller' => [Controller\PlaytimeController::class, 'playtime'],
    ],
    [
        'route' => '/changes/names',
        'methods' => ['GET'],
        'controller' => [Controller\ChangeController::class, 'name'],
    ],
    [
        'route' => '/ping',
        'methods' => ['GET'],
        'controller' => [Controller\PingController::class, 'ping'],
    ],
    [
        'route' => '/images/ranking',
        'methods' => ['GET'],
        'controller' => [Controller\ImageController::class, 'ranking'],
    ],
    [
        'route' => '/images/{world}-ranking.png',
        'methods' => ['GET'],
        'controller' => [Controller\ImageController::class, 'image'],
    ],
];
