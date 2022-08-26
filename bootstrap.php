<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;

const ROOT_DIR = __DIR__;

function dd(mixed $value): never
{
    var_dump($value);
    die;
}

require ROOT_DIR . '/vendor/autoload.php';

$config = require ROOT_DIR . '/config/config.php';

if (file_exists(ROOT_DIR . '/config/config.local.php')) {
    $configLocal = require ROOT_DIR . '/config/config.local.php';
    $config = array_merge($config, $configLocal);
}

$serviceContainer = new ServiceContainer($config['services']);

$serviceContainer->set('config', $config);
$serviceContainer->set('appEnv', $config['app_env']);

return $serviceContainer;
