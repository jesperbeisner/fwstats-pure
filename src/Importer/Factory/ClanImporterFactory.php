<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Service\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class ClanImporterFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): ClanImporter
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $serviceContainer->get(FreewarDumpServiceInterface::class);

        /** @var ClanRepository $clanRepository */
        $clanRepository = $serviceContainer->get(ClanRepository::class);

        /** @var ClanNameHistoryRepository $clanNamingHistoryRepository */
        $clanNamingHistoryRepository = $serviceContainer->get(ClanNameHistoryRepository::class);

        /** @var ClanDeletedHistoryRepository $clanDeletedHistoryRepository */
        $clanDeletedHistoryRepository = $serviceContainer->get(ClanDeletedHistoryRepository::class);

        /** @var ClanCreatedHistoryRepository $clanCreatedHistoryRepository */
        $clanCreatedHistoryRepository = $serviceContainer->get(ClanCreatedHistoryRepository::class);

        return new ClanImporter(
            $freewarDumpService,
            $clanRepository,
            $clanNamingHistoryRepository,
            $clanDeletedHistoryRepository,
            $clanCreatedHistoryRepository
        );
    }
}
