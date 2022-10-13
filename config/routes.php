<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Controller;

return [
    [
        'route' => '/',
        'methods' => ['GET'],
        'controller' => Controller\IndexController::class,
        'action' => 'index',
    ],
    [
        'route' => '/profile/{world}/{id}',
        'methods' => ['GET'],
        'controller' => Controller\ProfileController::class,
        'action' => 'profile',
    ],
    [
        'route' => '/playtime',
        'methods' => ['GET'],
        'controller' => Controller\PlaytimeController::class,
        'action' => 'playtime',
    ],
    [
        'route' => '/changes/names',
        'methods' => ['GET'],
        'controller' => Controller\ChangeController::class,
        'action' => 'name',
    ],
    [
        'route' => '/images/ranking',
        'methods' => ['GET'],
        'controller' => Controller\ImageController::class,
        'action' => 'ranking',
    ],
    [
        'route' => '/images/{world}-ranking.png',
        'methods' => ['GET'],
        'controller' => Controller\ImageController::class,
        'action' => 'image',
    ],
    [
        'route' => '/ping',
        'methods' => ['GET'],
        'controller' => Controller\PingController::class,
        'action' => 'ping',
    ],
    [
        'route' => '/admin/logs',
        'methods' => ['GET'],
        'controller' => Controller\LogController::class,
        'action' => 'logs',
    ],
    [
        'route' => '/login',
        'methods' => ['GET', 'POST'],
        'controller' => Controller\SecurityController::class,
        'action' => 'login',
    ],
    [
        'route' => '/logout',
        'methods' => ['GET', 'POST'],
        'controller' => Controller\SecurityController::class,
        'action' => 'logout',
    ],
];
