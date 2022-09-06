<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\IndexController;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Psr\Container\ContainerInterface;

final class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): IndexController
    {
        /** @var Request $request */
        $request = $serviceContainer->get(Request::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $serviceContainer->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $serviceContainer->get(ClanRepository::class);

        return new IndexController(
            $request,
            $playerRepository,
            $clanRepository
        );
    }
}
