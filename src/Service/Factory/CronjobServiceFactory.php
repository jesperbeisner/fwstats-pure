<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

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
use Jesperbeisner\Fwstats\Repository\CronjobRepository;
use Jesperbeisner\Fwstats\Service\CronjobService;

class CronjobServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): CronjobService
    {
        return new CronjobService(
            $container->get(CronjobRepository::class),
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
