<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Command\AbstractCommand;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\EndProcessInterface;
use Jesperbeisner\Fwstats\Interface\StartProcessInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Container;
use Jesperbeisner\Fwstats\Stdlib\DotEnvLoader;

require __DIR__ . '/vendor/autoload.php';

DotEnvLoader::load([__DIR__ . '/.env.php', __DIR__ . '/.env.local.php']);

/**
 * @var array{
 *     global: array<string, string|int|float|bool>,
 *     routes: array<array{route: string, methods: array<string>, controller: class-string<ControllerInterface>}>,
 *     startProcesses: array<class-string<StartProcessInterface>>,
 *     endProcesses: array<class-string<EndProcessInterface>>,
 *     commands: array<class-string<AbstractCommand>>,
 *     factories: array<string, class-string<FactoryInterface>>
 * } $configArray
 */
$configArray = require __DIR__ . '/config/config.php';
$config = new Config($configArray);

$container = new Container($config->getFactories());
$container->set(Config::class, $config);

return $container;
