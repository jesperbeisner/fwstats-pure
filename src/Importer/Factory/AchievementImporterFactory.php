<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;

final class AchievementImporterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): AchievementImporter
    {
        return new AchievementImporter(
            $container->get(FreewarDumpServiceInterface::class),
            $container->get(PlayerRepository::class),
            $container->get(AchievementRepository::class)
        );
    }
}
