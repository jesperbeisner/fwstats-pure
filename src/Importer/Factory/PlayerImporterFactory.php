<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerClanHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerXpHistoryRepository;
use Jesperbeisner\Fwstats\Service\PlayerStatusService;

final class PlayerImporterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlayerImporter
    {
        return new PlayerImporter(
            $container->get(FreewarDumpServiceInterface::class),
            $container->get(ClanRepository::class),
            $container->get(PlayerRepository::class),
            $container->get(PlayerNameHistoryRepository::class),
            $container->get(PlayerRaceHistoryRepository::class),
            $container->get(PlayerClanHistoryRepository::class),
            $container->get(PlayerProfessionHistoryRepository::class),
            $container->get(PlayerStatusHistoryRepository::class),
            $container->get(PlayerStatusService::class),
            $container->get(PlayerXpHistoryRepository::class),
        );
    }
}
