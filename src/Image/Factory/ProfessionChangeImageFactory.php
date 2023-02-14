<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image\Factory;

use Jesperbeisner\Fwstats\Image\ProfessionChangeImage;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class ProfessionChangeImageFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ProfessionChangeImage
    {
        return new ProfessionChangeImage(
            $container->get(Config::class)->getRootDir() . '/var',
            $container->get(Config::class)->getRootDir() . '/data/Roboto-Light.ttf',
            'profession-changes.png',
            $container->get(PlayerProfessionHistoryRepository::class),
        );
    }
}
