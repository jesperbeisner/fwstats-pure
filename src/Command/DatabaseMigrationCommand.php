<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Repository\MigrationRepository;
use RuntimeException;

final class DatabaseMigrationCommand extends AbstractCommand
{
    public static string $name = 'app:database-migration';
    public static string $description = 'Checks for new migrations and when found executes them.';

    private const MIGRATION_FILE_PATTERN = __DIR__ . '/../../migrations/*.sql';

    public function __construct(
        private readonly MigrationRepository $migrationRepository
    ) {
    }

    public function execute(): int
    {
        $this->startTime();
        $this->write("Starting the 'app:database-migration' command...");

        $this->migrationRepository->createMigrationsTable();

        if (false === $migrationFiles = glob(self::MIGRATION_FILE_PATTERN)) {
            throw new RuntimeException("An error occurred while 'globing' the migration files. :^)");
        }

        foreach ($migrationFiles as $migrationFile) {
            $fileName = basename($migrationFile);

            if (null !== $this->migrationRepository->findByFileName($fileName)) {
                $this->write("Skip '$fileName': Migration was already executed");
                continue;
            }

            /** @var string $sql */
            $sql = file_get_contents($migrationFile);

            $this->migrationRepository->executeMigration($fileName, $sql);
        }

        $this->write("Finished the 'app:database-migration' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
