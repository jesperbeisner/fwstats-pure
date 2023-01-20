<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;
use Jesperbeisner\Fwstats\Service\NameChangeImageService;
use Jesperbeisner\Fwstats\Service\RankingImageService;

final class AppCommand extends AbstractCommand
{
    public static string $name = 'app:run';
    public static string $description = 'Runs all needed imports and image creations in a single command.';

    public function __construct(
        private readonly ClanImporter $clanImporter,
        private readonly PlayerImporter $playerImporter,
        private readonly AchievementImporter $achievementImporter,
        private readonly PlaytimeImporter $playtimeImporter,
        private readonly RankingImageService $rankingImageService,
        private readonly NameChangeImageService $nameChangeImageService,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();

        $this->writeLine("Starting the 'app:run' command...");
        $this->writeLine();

        foreach (WorldEnum::cases() as $world) {
            $this->writeLine("Importing clans for world '$world->value'...");
            $this->clanImporter->import($world);

            $this->writeLine("Importing players for world '$world->value'...");
            $this->playerImporter->import($world);

            $this->writeLine("Importing achievements for world '$world->value'...");
            $this->achievementImporter->import($world);

            $this->writeLine("Importing playtime for world '$world->value'...");
            $this->playtimeImporter->import($world);

            $this->writeLine("Creating ranking image for world '$world->value'...");
            $this->rankingImageService->create($world);

            $this->writeLine("Creating name change ranking image for world '$world->value'...");
            $this->nameChangeImageService->create($world);

            $this->writeLine();
        }

        $this->writeLine("Finished the 'app:run' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
