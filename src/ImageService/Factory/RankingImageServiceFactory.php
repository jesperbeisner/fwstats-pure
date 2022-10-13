<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\ImageService\Factory;

use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

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

        return new RankingImageService(
            $config,
            $playerRepository,
            $clanRepository,
        );
    }
}
