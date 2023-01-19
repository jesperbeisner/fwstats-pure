<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Service\MigrationService;

final class DatabaseMigrationCommand extends AbstractCommand
{
    public static string $name = 'app:database-migration';
    public static string $description = 'Checks for new migrations and when found executes them.';

    public function __construct(
        private readonly MigrationService $migrationService,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();
        $this->writeLine("Starting the 'app:database-migration' command...");

        $this->migrationService->loadMigrations();

        $this->writeLine("Finished the 'app:database-migration' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
