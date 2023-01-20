<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\DatabaseInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\LoggerInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Database;
use PDO;

final class DatabaseFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): DatabaseInterface
    {
        /** @var Config $config */
        $config = $container->get(Config::class);
        $databaseFile = $config->getString('database_file');

        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        $pdo = new PDO(dsn: 'sqlite:' . $databaseFile, options: $options);

        $logger = null;
        if ($config->getAppEnv() !== 'prod') {
            $logger = $container->get(LoggerInterface::class);
        }

        return new Database($pdo, $logger);
    }
}
