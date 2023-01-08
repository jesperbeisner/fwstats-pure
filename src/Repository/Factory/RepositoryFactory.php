<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository\Factory;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\DatabaseInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\AbstractRepository;
use Throwable;

class RepositoryFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): AbstractRepository
    {
        /** @var DatabaseInterface $database */
        $database = $container->get(DatabaseInterface::class);

        try {
            $repository = new $serviceId($database);
        } catch (Throwable $e) {
            throw new RuntimeException(sprintf('Looks like service with id "%s" is not a valid repository class.', $serviceId));
        }

        if (!$repository instanceof AbstractRepository) {
            throw new RuntimeException(sprintf('Looks like "%s" is not a valid repository class.', $serviceId));
        }

        return $repository;
    }
}
