<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\HomeController;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Database;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class HomeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $serviceName): HomeController
    {
        /** @var Database $database */
        $database = $container->get(Database::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $database->getRepository(PlayerRepository::class);

        return new HomeController($playerRepository);
    }
}
