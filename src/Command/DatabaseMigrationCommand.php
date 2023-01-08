<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use RuntimeException;

final class DatabaseMigrationCommand extends AbstractCommand
{
    public static string $name = 'app:database-migration';
    public static string $description = 'Checks for new migrations and when found executes them.';

    public function __construct(
        private readonly string $migrationsFolder,
        private readonly MigrationRepository $migrationRepository,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();
        $this->writeLine("Starting the 'app:database-migration' command...");

        $this->migrationRepository->createMigrationsTable();

        if (false === $migrationFiles = glob($this->migrationsFolder . '/*.sql')) {
            throw new RuntimeException("An error occurred while 'globing' the migration files. :^)");
        }

        foreach ($migrationFiles as $migrationFile) {
            $fileName = basename($migrationFile);

            if (null !== $this->migrationRepository->findByFileName($fileName)) {
                $this->writeLine("Skip '$fileName': Migration was already executed");
                continue;
            }

            if (false === $sql = file_get_contents($migrationFile)) {
                throw new RuntimeException(sprintf('Could not get content of migration file "%s".', $migrationFile));
            }

            $this->migrationRepository->executeMigration($fileName, $sql);
            $this->writeLine("Hit: '$fileName' was executed");
        }

        $this->writeLine("Finished the 'app:database-migration' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
