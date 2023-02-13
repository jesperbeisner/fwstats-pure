<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Image\Factory;

use Jesperbeisner\Fwstats\Image\BanAndDeletionImage;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class BanAndDeletionImageFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): BanAndDeletionImage
    {
        return new BanAndDeletionImage(
            $container->get(Config::class),
            $container->get(PlayerStatusHistoryRepository::class),
        );
    }
}
