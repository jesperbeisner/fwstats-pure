<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\IndexController;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final class IndexControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): IndexController
    {
        /** @var Request $request */
        $request = $container->get(Request::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $container->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $container->get(ClanRepository::class);

        return new IndexController(
            $request,
            $playerRepository,
            $clanRepository
        );
    }
}
