<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer\Factory;

use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\Interface\FreewarDumpServiceInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class AchievementImporterFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): AchievementImporter
    {
        /** @var FreewarDumpServiceInterface $freewarDumpService */
        $freewarDumpService = $container->get(FreewarDumpServiceInterface::class);

        /** @var PlayerRepository $playerRepository */
        $playerRepository = $container->get(PlayerRepository::class);

        /** @var AchievementRepository $achievementRepository */
        $achievementRepository = $container->get(AchievementRepository::class);

        return new AchievementImporter(
            $freewarDumpService,
            $playerRepository,
            $achievementRepository
        );
    }
}
