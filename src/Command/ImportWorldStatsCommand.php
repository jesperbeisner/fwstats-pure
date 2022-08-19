<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Importer\AchievementImporter;
use Jesperbeisner\Fwstats\Importer\ClanImporter;
use Jesperbeisner\Fwstats\Importer\ImportResult;
use Jesperbeisner\Fwstats\Importer\PlayerImporter;

final class ImportWorldStatsCommand extends AbstractCommand
{
    public static string $name = 'app:import-world-stats';
    public static string $description = "Imports the current world stats into the database.";

    public function __construct(
        private readonly ClanImporter $clanImporter,
        private readonly PlayerImporter $playerImporter,
        private readonly AchievementImporter $achievementImporter,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();
        $this->writeLine("Starting the 'app:import-world-stats' command...");
        $this->writeLine();

        foreach (WorldEnum::cases() as $world) {
            $clanImporterResult = $this->clanImporter->import($world);
            $this->writeImportResultMessages($clanImporterResult);
            $this->writeLine();

            $playerImporterResult = $this->playerImporter->import($world);
            $this->writeImportResultMessages($playerImporterResult);
            $this->writeLine();

            $achievementImporterResult = $this->achievementImporter->import($world);
            $this->writeImportResultMessages($achievementImporterResult);
            $this->writeLine();
        }

        $this->writeLine("Finished the 'app:import-world-stats' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }

    private function writeImportResultMessages(ImportResult $importResult): void
    {
        foreach ($importResult->getMessages() as $message) {
            $this->writeLine($message);
        }
    }
}
