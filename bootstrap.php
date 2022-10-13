<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\DotEnvPhpLoader;
use Jesperbeisner\Fwstats\Stdlib\Container;
use Jesperbeisner\Fwstats\Stdlib\Router;

require __DIR__ . '/vendor/autoload.php';

DotEnvPhpLoader::load([__DIR__ . '/.env.php', __DIR__ . '/.env.local.php']);

$config = new Config(__DIR__ . '/config/config.php');
$router = new Router(__DIR__ . '/config/routes.php');

$serviceContainer = new Container(__DIR__ . '/config/services.php');

$serviceContainer->set(Config::class, $config);
$serviceContainer->set(Router::class, $router);

return $serviceContainer;
