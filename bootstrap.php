<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;

require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/config/config.php';

if (file_exists(__DIR__ . '/config/config.local.php')) {
    $configLocal = require __DIR__ . '/config/config.local.php';
    $config = array_merge($config, $configLocal);
}

$serviceContainer = new ServiceContainer($config['services']);

$serviceContainer->set('config', $config);
$serviceContainer->set('appEnv', $config['app_env']);
$serviceContainer->set('rootDir', __DIR__);

return $serviceContainer;
