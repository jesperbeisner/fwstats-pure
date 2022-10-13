<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerClanHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Service\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Service\PlayerStatusService;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class PlayerImporterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlayerImporter
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $container->get(FreewarDumpServiceInterface::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $container->get(ClanRepository::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $container->get(PlayerRepository::class);

        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $container->get(PlayerNameHistoryRepository::class);

        /** @var PlayerRaceHistoryRepository $playerRaceHistoryRepository */
        $playerRaceHistoryRepository = $container->get(PlayerRaceHistoryRepository::class);

        /** @var PlayerClanHistoryRepository $playerClanHistoryRepository */
        $playerClanHistoryRepository = $container->get(PlayerClanHistoryRepository::class);

        /** @var PlayerProfessionHistoryRepository $playerProfessionHistoryRepository */
        $playerProfessionHistoryRepository = $container->get(PlayerProfessionHistoryRepository::class);

        /** @var PlayerStatusHistoryRepository $playerStatusHistoryRepository */
        $playerStatusHistoryRepository = $container->get(PlayerStatusHistoryRepository::class);

        /** @var PlayerStatusService $playerStatusService */
        $playerStatusService = $container->get(PlayerStatusService::class);

        return new PlayerImporter(
            $freewarDumpService,
            $clanRepository,
            $playerRepository,
            $playerNameHistoryRepository,
            $playerRaceHistoryRepository,
            $playerClanHistoryRepository,
            $playerProfessionHistoryRepository,
            $playerStatusHistoryRepository,
            $playerStatusService
        );
    }
}
