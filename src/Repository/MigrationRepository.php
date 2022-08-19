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

    /**
     * @return array{id: int, name: string, created: string}|null
     */
    public function findByFileName(string $fileName): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE name = :migration";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['migration' => $fileName]);

        $result = $stmt->fetchAll();
        if (count($result) === 0) {
            return null;
        }

        /** @var array{id: int, name: string, created: string} $result */

        return $result;
    }

    public function executeMigration(string $fileName, string $sql): void
    {
        $this->pdo->exec($sql);

        $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (name) VALUES (:migrationName)");
        $stmt->execute(['migrationName' => $fileName]);
    }
}
