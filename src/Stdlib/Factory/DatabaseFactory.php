<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Database;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;
use PDO;

final class DatabaseFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $serviceName): Database
    {
        return new Database(new PDO('sqlite:' . ROOT_DIR . '/database/sqlite.db'));
    }
}
