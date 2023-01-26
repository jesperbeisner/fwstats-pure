<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Migration;

final class MigrationRepository extends AbstractRepository
{
    public function insert(Migration $migration): Migration
    {
        $sql = "INSERT INTO migrations (name, created) VALUES (:name, :created)";

        $id = $this->database->insert($sql, [
            'name' => $migration->name,
            'created' => $migration->created->format('Y-m-d H:i:s'),
        ]);

        return Migration::withId($id, $migration);
    }

    public function createMigrationsTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS migrations (id INTEGER PRIMARY KEY, name TEXT NOT NULL, created DATETIME NOT NULL)";
        $this->database->execute($sql);

        $sql = "CREATE UNIQUE INDEX IF NOT EXISTS migrations_name_unique_index ON migrations(name)";
        $this->database->execute($sql);
    }

    /**
     * @return null|Migration
     */
    public function findByFileName(string $fileName): ?Migration
    {
        $sql = "SELECT * FROM migrations WHERE name = :migration";

        /** @var null|array{id: int, name: string, created: string} $result */
        $result = $this->database->selectOne($sql, [
            'migration' => $fileName,
        ]);

        if ($result === null) {
            return null;
        }

        return $this->hydrateMigration($result);
    }

    public function execute(string $statement): void
    {
        $this->database->execute($statement);
    }

    /**
     * @param array{id: int, name: string, created: string} $row
     */
    private function hydrateMigration(array $row): Migration
    {
        return new Migration(
            $row['id'],
            $row['name'],
            new DateTimeImmutable($row['created']),
        );
    }
}
