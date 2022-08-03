<?php

declare(strict_types=1);

return [
    'app_env' => 'dev',

    'routes' => require __DIR__ . '/routes.config.php',
    'services' => require __DIR__ . '/services.config.php',
    'commands' => require __DIR__ . '/commands.config.php',
];
