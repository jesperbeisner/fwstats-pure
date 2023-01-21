<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Factory;

use Jesperbeisner\Fwstats\Action\ResetActionFreewarAction;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerClanHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;

class ResetActionFreewarActionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ResetActionFreewarAction
    {
        return new ResetActionFreewarAction(
            $container->get(AchievementRepository::class),
            $container->get(ClanCreatedHistoryRepository::class),
            $container->get(ClanDeletedHistoryRepository::class),
            $container->get(ClanNameHistoryRepository::class),
            $container->get(ClanRepository::class),
            $container->get(PlayerActiveSecondRepository::class),
            $container->get(PlayerClanHistoryRepository::class),
            $container->get(PlayerNameHistoryRepository::class),
            $container->get(PlayerProfessionHistoryRepository::class),
            $container->get(PlayerRaceHistoryRepository::class),
            $container->get(PlayerRepository::class),
            $container->get(PlayerStatusHistoryRepository::class),
        );
    }
}
