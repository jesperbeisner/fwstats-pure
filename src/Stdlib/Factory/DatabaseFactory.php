<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Database;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\DatabaseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use PDO;

final class DatabaseFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseInterface
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $pdo = new PDO(dsn: 'sqlite:' . $config->getRootDir() . '/data/database/sqlite.db', options: $options);

        return new Database($pdo);
    }
}
