<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Service\RaceChangeImageService;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class RaceChangeImageServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RaceChangeImageService
    {
        return new RaceChangeImageService(
            $container->get(Config::class),
            $container->get(PlayerRaceHistoryRepository::class),
        );
    }
}
