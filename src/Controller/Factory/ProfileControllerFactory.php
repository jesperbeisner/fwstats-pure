<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ProfileController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\PlaytimeService;
use Jesperbeisner\Fwstats\Service\XpService;

final readonly class ProfileControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ProfileController
    {
        return new ProfileController(
            $container->get(PlayerRepository::class),
            $container->get(XpService::class),
            $container->get(PlaytimeService::class),
            $container->get(PlayerNameHistoryRepository::class),
            $container->get(PlayerRaceHistoryRepository::class),
            $container->get(PlayerProfessionHistoryRepository::class)
        );
    }
}
