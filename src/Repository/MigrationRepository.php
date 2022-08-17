<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

final class MigrationRepository extends AbstractRepository
{
    private string $table = 'migrations';

    public function createMigrationsTable(): void
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS {$this->table} (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL UNIQUE,
                created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        SQL;

        $this->pdo->exec($sql);
    }

    public function findByFileName(string $fileName): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE name = :migration";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['migration' => $fileName]);

        /** @var mixed[] $result */
        $result = $stmt->fetchAll();

        return count($result) === 0 ? null : $result;
    }

    public function executeMigration(string $fileName, string $sql): void
    {
        $this->pdo->exec($sql);

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (name) VALUES (:migrationName)");
        $stmt->execute(['migrationName' => $fileName]);
    }
}
