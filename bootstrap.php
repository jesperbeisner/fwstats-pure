<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;

const ROOT_DIR = __DIR__;

require ROOT_DIR . '/vendor/autoload.php';

$config = require ROOT_DIR . '/config/config.php';

$serviceContainer = new ServiceContainer($config['services']);

$serviceContainer->set('config', $config);
$serviceContainer->set('appEnv', $config['app_env']);

return $serviceContainer;
