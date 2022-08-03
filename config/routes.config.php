<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Controller;

return [
    [
        'route' => '/',
        'methods' => ['GET', 'POST'],
        'controller' => Controller\HomeController::class,
    ],
];
