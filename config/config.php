<?php

declare(strict_types=1);

return [
    'app_env' => $_ENV['APP_ENV'] ?? throw new RuntimeException('No "APP_ENV" value available.'),
    'root_dir' => dirname(__DIR__),
];
