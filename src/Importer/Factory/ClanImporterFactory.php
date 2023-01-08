<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;

final class ClanImporterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ClanImporter
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $container->get(FreewarDumpServiceInterface::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $container->get(ClanRepository::class);

        /** @var ClanNameHistoryRepository $clanNamingHistoryRepository */
        $clanNamingHistoryRepository = $container->get(ClanNameHistoryRepository::class);

        /** @var ClanDeletedHistoryRepository $clanDeletedHistoryRepository */
        $clanDeletedHistoryRepository = $container->get(ClanDeletedHistoryRepository::class);

        /** @var ClanCreatedHistoryRepository $clanCreatedHistoryRepository */
        $clanCreatedHistoryRepository = $container->get(ClanCreatedHistoryRepository::class);

        return new ClanImporter(
            $freewarDumpService,
            $clanRepository,
            $clanNamingHistoryRepository,
            $clanDeletedHistoryRepository,
            $clanCreatedHistoryRepository
        );
    }
}
