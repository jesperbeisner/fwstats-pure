<?php

declare(strict_types=1);

return [
    'app_env' => $_ENV['APP_ENV'] ?? 'prod',
    'root_dir' => dirname(__DIR__),
];
