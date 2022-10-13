<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\AppCommand;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

class AppCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): AppCommand
    {
        /** @var ClanImporter $clanImporter */
        $clanImporter = $container->get(ClanImporter::class);

        /** @var PlayerImporter $playerImporter */
        $playerImporter = $container->get(PlayerImporter::class);

        /** @var AchievementImporter $achievementImporter */
        $achievementImporter = $container->get(AchievementImporter::class);

        /** @var RankingImageService $rankingImageService */
        $rankingImageService = $container->get(RankingImageService::class);

        /** @var PlaytimeImporter $playtimeImporter */
        $playtimeImporter = $container->get(PlaytimeImporter::class);

        return new AppCommand(
            $clanImporter,
            $playerImporter,
            $achievementImporter,
            $rankingImageService,
            $playtimeImporter,
        );
    }
}
