<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image\Factory;

use Jesperbeisner\Fwstats\Image\RaceChangeImage;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class RaceChangeImageFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RaceChangeImage
    {
        return new RaceChangeImage(
            $container->get(Config::class)->getRootDir() . '/var',
            $container->get(Config::class)->getRootDir() . '/data/Roboto-Light.ttf',
            'race-changes.png',
            $container->get(PlayerRaceHistoryRepository::class),
        );
    }
}
