<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Service\ProfessionChangeImageService;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class ProfessionChangeImageServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ProfessionChangeImageService
    {
        return new ProfessionChangeImageService(
            $container->get(Config::class),
            $container->get(PlayerProfessionHistoryRepository::class),
        );
    }
}
