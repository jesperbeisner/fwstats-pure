<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Stdlib\Exception\DatabaseException;

final class MigrationRepository extends AbstractRepository
{
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

    /**
     * @return array{id: int, name: string, created: string}|null
     */
    public function findByFileName(string $fileName): ?array
    {
        $sql = "SELECT * FROM migrations WHERE name = :migration";

        /** @var array<array{id: int, name: string, created: string}> $result */
        $result = $this->database->select($sql, [
            'migration' => $fileName,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException('How can there be more than 1 migration for a single file name?');
        }

        return $result[0];
    }

    public function executeMigration(string $migrationName, string $migrationSql): void
    {
        $sql = "INSERT INTO migrations (name) VALUES (:migrationName)";

        $this->database->execute($migrationSql);

        $this->database->insert($sql, [
            'migrationName' => $migrationName,
        ]);
    }
}
