<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\ImportWorldStatsCommand;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNamingHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class ImportWorldStatsCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $serviceName): ImportWorldStatsCommand
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $container->get(FreewarDumpServiceInterface::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $container->get(ClanRepository::class);

        /** @var ClanNamingHistoryRepository $clanNamingHistoryRepository */
        $clanNamingHistoryRepository = $container->get(ClanNamingHistoryRepository::class);

        /** @var ClanCreatedHistoryRepository $clanCreatedHistoryRepository */
        $clanCreatedHistoryRepository = $container->get(ClanCreatedHistoryRepository::class);

        /** @var ClanDeletedHistoryRepository $clanDeletedHistoryRepository */
        $clanDeletedHistoryRepository = $container->get(ClanDeletedHistoryRepository::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $container->get(PlayerRepository::class);

        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $container->get(PlayerNameHistoryRepository::class);

        return new ImportWorldStatsCommand(
            $freewarDumpService,
            $clanRepository,
            $clanNamingHistoryRepository,
            $clanCreatedHistoryRepository,
            $clanDeletedHistoryRepository,
            $playerRepository,
            $playerNameHistoryRepository
        );
    }
}
