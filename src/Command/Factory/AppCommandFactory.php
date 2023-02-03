<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\AppCommand;
use Jesperbeisner\Fwstats\Image\NameChangeImage;
use Jesperbeisner\Fwstats\Image\ProfessionChangeImage;
use Jesperbeisner\Fwstats\Image\RaceChangeImage;
use Jesperbeisner\Fwstats\Image\RankingImage;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

class AppCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): AppCommand
    {
        return new AppCommand(
            $container->get(ClanImporter::class),
            $container->get(PlayerImporter::class),
            $container->get(AchievementImporter::class),
            $container->get(PlaytimeImporter::class),
            $container->get(RankingImage::class),
            $container->get(NameChangeImage::class),
            $container->get(RaceChangeImage::class),
            $container->get(ProfessionChangeImage::class),
        );
    }
}
