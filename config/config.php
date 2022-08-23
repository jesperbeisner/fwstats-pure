<?php

declare(strict_types=1);

return [
    'app_env' => 'dev',
    'routes' => require __DIR__ . '/routes.php',
    'commands' => require __DIR__ . '/commands.php',
    'services' => require __DIR__ . '/services.php',
];
