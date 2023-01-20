<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Service\PlaytimeService;

class PlaytimeServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlaytimeService
    {
        return new PlaytimeService(
            $container->get(PlayerActiveSecondRepository::class),
        );
    }
}
