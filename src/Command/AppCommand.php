<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\ImageService\RankingImageService;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;
use Jesperbeisner\Fwstats\Importer\PlaytimeImporter;

final class AppCommand extends AbstractCommand
{
    public static string $name = 'app:run';
    public static string $description = 'Runs all needed imports and image creations in a single command.';

    public function __construct(
        private readonly ClanImporter $clanImporter,
        private readonly PlayerImporter $playerImporter,
        private readonly AchievementImporter $achievementImporter,
        private readonly RankingImageService $rankingImageService,
        private readonly PlaytimeImporter $playtimeImporter,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();

        $this->writeLine("Starting the 'app:run' command...");
        $this->writeLine();

        foreach (WorldEnum::cases() as $world) {
            $this->clanImporter->import($world);
            $this->playerImporter->import($world);
            $this->achievementImporter->import($world);

            $this->playtimeImporter->import($world);

            $this->rankingImageService->create($world);
        }

        $this->writeLine("Finished the 'app:run' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
