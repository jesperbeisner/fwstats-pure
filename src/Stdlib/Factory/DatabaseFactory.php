<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Database;
use Jesperbeisner\Fwstats\Stdlib\Interface\DatabaseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;
use PDO;

final class DatabaseFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): DatabaseInterface
    {
        /** @var string $rootDir */
        $rootDir = $serviceContainer->get('rootDir');

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        return new Database(
            new PDO(dsn: 'sqlite:' . $rootDir . '/data/database/sqlite.db', options: $options),
        );
    }
}
