<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository\Factory;

use Jesperbeisner\Fwstats\Repository\AbstractRepository;
use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use PDO;
use Psr\Container\ContainerInterface;
use Throwable;

class RepositoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): AbstractRepository
    {
        /** @var PDO $pdo */
        $pdo = $serviceContainer->get(PDO::class);

        try {
            $repository = new $serviceName($pdo);
        } catch (Throwable $e) {
            throw new RuntimeException("Looks like '$serviceName' is not a valid repository class.");
        }

        if (!($repository instanceof AbstractRepository)) {
            throw new RuntimeException("Looks like '$serviceName' is not a valid repository class.");
        }

        return $repository;
    }
}
