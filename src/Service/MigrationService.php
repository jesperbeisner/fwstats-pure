<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use DateTimeImmutable;
use DirectoryIterator;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Model\Migration;
use Jesperbeisner\Fwstats\Repository\MigrationRepository;

final readonly class MigrationService
{
    public function __construct(
        private string $migrationsDirectory,
        private MigrationRepository $migrationRepository,
    ) {
    }

    public function loadMigrations(): void
    {
        $migrationFiles = [];
        $this->migrationRepository->createMigrationsTable();

        /** @var DirectoryIterator $file */
        foreach (new DirectoryIterator($this->migrationsDirectory) as $file) {
            if (!$file->isFile()) {
                continue;
            }

            if (!str_ends_with($file->getFilename(), '-migration.php')) {
                continue;
            }

            if (null !== $this->migrationRepository->findByFileName($file->getFilename())) {
                continue;
            }

            $migrationFiles[$file->getFilename()] = $file->getPathname();
        }

        ksort($migrationFiles);

        foreach ($migrationFiles as $fileName => $filePath) {
            $statements = require $filePath;

            if (!is_array($statements)) {
                throw new RuntimeException(sprintf('Migrations file "%s" did not return an array.', $fileName));
            }

            foreach ($statements as $statement) {
                if (!is_string($statement)) {
                    throw new RuntimeException(sprintf('Migrations file "%s" did not return only strings.', $fileName));
                }

                $this->migrationRepository->execute($statement);
            }

            $this->migrationRepository->insert(new Migration(null, $fileName, new DateTimeImmutable()));
        }
    }
}
