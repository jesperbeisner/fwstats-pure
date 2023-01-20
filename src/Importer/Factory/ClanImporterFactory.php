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
        return new ClanImporter(
            $container->get(FreewarDumpServiceInterface::class),
            $container->get(ClanRepository::class),
            $container->get(ClanNameHistoryRepository::class),
            $container->get(ClanDeletedHistoryRepository::class),
            $container->get(ClanCreatedHistoryRepository::class)
        );
    }
}
