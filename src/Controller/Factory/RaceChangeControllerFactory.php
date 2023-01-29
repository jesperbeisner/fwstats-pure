<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\RaceChangeController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;

final readonly class RaceChangeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RaceChangeController
    {
        return new RaceChangeController(
            $container->get(PlayerRaceHistoryRepository::class),
        );
    }
}
