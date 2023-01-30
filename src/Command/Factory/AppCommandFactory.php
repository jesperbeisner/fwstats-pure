<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\AppCommand;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Service\NameChangeImageService;
use Jesperbeisner\Fwstats\Service\RaceChangeImageService;
use Jesperbeisner\Fwstats\Service\RankingImageService;

class AppCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): AppCommand
    {
        return new AppCommand(
            $container->get(ClanImporter::class),
            $container->get(PlayerImporter::class),
            $container->get(AchievementImporter::class),
            $container->get(PlaytimeImporter::class),
            $container->get(RankingImageService::class),
            $container->get(NameChangeImageService::class),
            $container->get(RaceChangeImageService::class),
        );
    }
}
