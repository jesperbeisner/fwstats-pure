<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\DotEnvLoader;
use Jesperbeisner\Fwstats\Stdlib\Container;
use Jesperbeisner\Fwstats\Stdlib\Interface\RouterInterface;
use Jesperbeisner\Fwstats\Stdlib\Router;

require __DIR__ . '/vendor/autoload.php';

DotEnvLoader::load([__DIR__ . '/.env.php', __DIR__ . '/.env.local.php']);

/** @var array<string, string|int|float|bool> $configArray */
$configArray = require __DIR__ . '/config/config.php';
$config = new Config($configArray);

/** @var array<array{route: string, methods: array<string>, controller: string, action: string}> $routesArray */
$routesArray = require __DIR__ . '/config/routes.php';
$router = new Router($routesArray);

$serviceContainer = new Container(__DIR__ . '/config/services.php');

$serviceContainer->set(Config::class, $config);
$serviceContainer->set(RouterInterface::class, $router);

return $serviceContainer;
