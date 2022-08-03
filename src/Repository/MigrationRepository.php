<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

final class MigrationRepository extends AbstractRepository
{
    protected const TABLE_NAME = 'migrations';

    public function createMigrationsTable(): void
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL UNIQUE,
                created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        SQL;

        $this->database->execute($sql);
    }

    public function findByFileName(string $fileName): ?array
    {
        $statement = $this->database->prepare("SELECT * FROM migrations WHERE name = :migration");
        $statement->execute(['migration' => $fileName]);

        /** @var mixed[] $result */
        $result = $statement->fetchAll();

        if (count($result) === 0) {
            return null;
        }

        return $result;
    }

    public function executeMigration(string $fileName, string $sql): void
    {
        $this->database->execute($sql);

        $statement = $this->database->prepare("INSERT INTO migrations (name) VALUES (:migrationName)");
        $statement->execute(['migrationName' => $fileName]);
    }
}
