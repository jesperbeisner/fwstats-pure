<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;
use PDO;

final class PdoFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): PDO
    {
        $options = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        return new PDO(dsn: 'sqlite:' . ROOT_DIR . '/data/database/sqlite.db', options: $options);
    }
}
