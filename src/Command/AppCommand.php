<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Image\BanAndDeletionImage;
use Jesperbeisner\Fwstats\Image\NameChangeImage;
use Jesperbeisner\Fwstats\Image\ProfessionChangeImage;
use Jesperbeisner\Fwstats\Image\RaceChangeImage;
use Jesperbeisner\Fwstats\Image\RankingImage;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;

final class AppCommand extends AbstractCommand
{
    public static string $name = 'app:run';
    public static string $description = 'Runs all needed imports and image creations in a single command.';

    public function __construct(
        private readonly string $appEnv,
        private readonly ClanImporter $clanImporter,
        private readonly PlayerImporter $playerImporter,
        private readonly AchievementImporter $achievementImporter,
        private readonly PlaytimeImporter $playtimeImporter,
        private readonly RankingImage $rankingImage,
        private readonly NameChangeImage $nameChangeImage,
        private readonly RaceChangeImage $raceChangeImage,
        private readonly ProfessionChangeImage $professionChangeImage,
        private readonly BanAndDeletionImage $banAndDeletionImage,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();

        $this->writeLine("Starting the 'app:run' command...");
        $this->writeLine();

        foreach (WorldEnum::cases() as $world) {
            // Only import dumps in prod env. Use fixtures in dev.
            if ($this->appEnv === 'prod') {
                $this->writeLine("Importing clans for world '$world->value'...");
                $this->clanImporter->import($world);

                $this->writeLine("Importing players for world '$world->value'...");
                $this->playerImporter->import($world);

                $this->writeLine("Importing achievements for world '$world->value'...");
                $this->achievementImporter->import($world);

                $this->writeLine("Importing playtime for world '$world->value'...");
                $this->playtimeImporter->import($world);
            }

            $this->writeLine("Creating ranking image for world '$world->value'...");
            $this->rankingImage->create($world);

            $this->writeLine("Creating name change image for world '$world->value'...");
            $this->nameChangeImage->create($world);

            $this->writeLine("Creating race change image for world '$world->value'...");
            $this->raceChangeImage->create($world);

            $this->writeLine("Creating profession change image for world '$world->value'...");
            $this->professionChangeImage->create($world);

            $this->writeLine("Creating ban and deletion image for world '$world->value'...");
            $this->banAndDeletionImage->create($world);

            $this->writeLine();
        }

        $this->writeLine("Finished the 'app:run' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
