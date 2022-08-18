<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Command\ImportWorldStatsCommand;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class ImportWorldStatsCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): ImportWorldStatsCommand
    {
        /** @var ClanImporter $clanImporter */
        $clanImporter = $serviceContainer->get(ClanImporter::class);

        /** @var PlayerImporter $playerImporter */
        $playerImporter = $serviceContainer->get(PlayerImporter::class);

        /** @var AchievementImporter $achievementImporter */
        $achievementImporter = $serviceContainer->get(AchievementImporter::class);

        return new ImportWorldStatsCommand(
            $clanImporter,
            $playerImporter,
            $achievementImporter
        );
    }
}
