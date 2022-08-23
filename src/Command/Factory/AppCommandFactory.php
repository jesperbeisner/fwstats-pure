<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\AppCommand;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class AppCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): AppCommand
    {
        /** @var ClanImporter $clanImporter */
        $clanImporter = $serviceContainer->get(ClanImporter::class);

        /** @var PlayerImporter $playerImporter */
        $playerImporter = $serviceContainer->get(PlayerImporter::class);

        /** @var AchievementImporter $achievementImporter */
        $achievementImporter = $serviceContainer->get(AchievementImporter::class);

        /** @var RankingImageService $rankingImageService */
        $rankingImageService = $serviceContainer->get(RankingImageService::class);

        /** @var PlaytimeImporter $playtimeImporter */
        $playtimeImporter = $serviceContainer->get(PlaytimeImporter::class);

        return new AppCommand(
            $clanImporter,
            $playerImporter,
            $achievementImporter,
            $rankingImageService,
            $playtimeImporter,
        );
    }
}
