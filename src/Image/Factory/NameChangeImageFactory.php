<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image\Factory;

use Jesperbeisner\Fwstats\Image\NameChangeImage;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class NameChangeImageFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): NameChangeImage
    {
        return new NameChangeImage(
            $container->get(Config::class)->getRootDir() . '/var',
            $container->get(Config::class)->getRootDir() . '/data/Roboto-Light.ttf',
            'name-changes.png',
            $container->get(PlayerNameHistoryRepository::class),
        );
    }
}
