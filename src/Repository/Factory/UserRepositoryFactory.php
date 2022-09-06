<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository\Factory;

use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\DatabaseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class UserRepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): UserRepository
    {
        /** @var DatabaseInterface $database */
        $database = $serviceContainer->get(DatabaseInterface::class);

        return new UserRepository(
            $database,
        );
    }
}
