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
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Service\PlayerStatusService;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class PlayerImporterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): PlayerImporter
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $serviceContainer->get(FreewarDumpServiceInterface::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $serviceContainer->get(ClanRepository::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $serviceContainer->get(PlayerRepository::class);

        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $serviceContainer->get(PlayerNameHistoryRepository::class);

        /** @var PlayerRaceHistoryRepository $playerRaceHistoryRepository */
        $playerRaceHistoryRepository = $serviceContainer->get(PlayerRaceHistoryRepository::class);

        /** @var PlayerClanHistoryRepository $playerClanHistoryRepository */
        $playerClanHistoryRepository = $serviceContainer->get(PlayerClanHistoryRepository::class);

        /** @var PlayerProfessionHistoryRepository $playerProfessionHistoryRepository */
        $playerProfessionHistoryRepository = $serviceContainer->get(PlayerProfessionHistoryRepository::class);

        /** @var PlayerStatusHistoryRepository $playerStatusHistoryRepository */
        $playerStatusHistoryRepository = $serviceContainer->get(PlayerStatusHistoryRepository::class);

        /** @var PlayerStatusService $playerStatusService */
        $playerStatusService = $serviceContainer->get(PlayerStatusService::class);

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
