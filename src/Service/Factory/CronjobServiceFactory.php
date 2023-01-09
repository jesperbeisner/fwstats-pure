<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\CronjobRepository;
use Jesperbeisner\Fwstats\Service\CronjobService;
use Jesperbeisner\Fwstats\Service\RankingImageService;

class CronjobServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): CronjobService
    {
        /** @var CronjobRepository $cronjobRepository */
        $cronjobRepository = $container->get(CronjobRepository::class);

        /** @var ClanImporter $clanImporter */
        $clanImporter = $container->get(ClanImporter::class);

        /** @var PlayerImporter $playerImporter */
        $playerImporter = $container->get(PlayerImporter::class);

        /** @var AchievementImporter $achievementImporter */
        $achievementImporter = $container->get(AchievementImporter::class);

        /** @var PlaytimeImporter $playtimeImporter */
        $playtimeImporter = $container->get(PlaytimeImporter::class);

        /** @var RankingImageService $rankingImageService */
        $rankingImageService = $container->get(RankingImageService::class);

        return new CronjobService($cronjobRepository, $clanImporter, $playerImporter, $achievementImporter, $playtimeImporter, $rankingImageService);
    }
}
