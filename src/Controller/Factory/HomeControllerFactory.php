<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\HomeController;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class HomeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $serviceName): HomeController
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $container->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $container->get(ClanRepository::class);

        return new HomeController($playerRepository, $clanRepository);
    }
}
