<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\DatabaseFixtureCommand;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class DatabaseFixtureCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): DatabaseFixtureCommand
    {
        /** @var mixed[] $config */
        $config = $serviceContainer->get('config');

        /** @var string $appEnv */
        $appEnv = $config['app_env'];

        return new DatabaseFixtureCommand($appEnv);
    }
}
