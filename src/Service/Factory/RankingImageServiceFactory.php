<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\RankingImageService;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class RankingImageServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RankingImageService
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $container->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $container->get(ClanRepository::class);

        return new RankingImageService($config, $playerRepository, $clanRepository);
    }
}
