<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\ImageService\Factory;

use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class RankingImageServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): RankingImageService
    {
        /** @var PlayerRepository $playerRepository */
        $playerRepository = $serviceContainer->get(PlayerRepository::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $serviceContainer->get(ClanRepository::class);

        return new RankingImageService($playerRepository, $clanRepository);
    }
}
