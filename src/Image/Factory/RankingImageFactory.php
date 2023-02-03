<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image\Factory;

use Jesperbeisner\Fwstats\Image\RankingImage;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class RankingImageFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RankingImage
    {
        return new RankingImage(
            $container->get(Config::class),
            $container->get(PlayerRepository::class),
            $container->get(ClanRepository::class),
        );
    }
}
