<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Controller;

return [
    [
        'route' => '/',
        'methods' => ['GET'],
        'controller' => Controller\IndexController::class,
    ],
    [
        'route' => '/profile/{world}/{id}',
        'methods' => ['GET'],
        'controller' => Controller\ProfileController::class,
    ],
    [
        'route' => '/playtime',
        'methods' => ['GET'],
        'controller' => Controller\PlaytimeController::class,
    ],
    [
        'route' => '/changes/names',
        'methods' => ['GET'],
        'controller' => Controller\ChangeController::class,
    ],
    [
        'route' => '/images/ranking',
        'methods' => ['GET'],
        'controller' => Controller\ImageController::class,
    ],
    [
        'route' => '/images/{world}-ranking.png',
        'methods' => ['GET'],
        'controller' => Controller\ImageRenderController::class,
    ],
    [
        'route' => '/status',
        'methods' => ['GET'],
        'controller' => Controller\StatusController::class,
    ],
    [
        'route' => '/admin/logs',
        'methods' => ['GET'],
        'controller' => Controller\LogController::class,
    ],
    [
        'route' => '/login',
        'methods' => ['GET', 'POST'],
        'controller' => Controller\LoginController::class,
    ],
    [
        'route' => '/logout',
        'methods' => ['GET', 'POST'],
        'controller' => Controller\LogoutController::class,
    ],
];
