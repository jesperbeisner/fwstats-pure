<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Model\Migration;

final class MigrationRepository extends AbstractRepository
{
    public function create(Migration $migration): Migration
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

        /** @var array<array{id: int, name: string, created: string}> $result */
        $result = $this->database->select($sql, [
            'migration' => $fileName,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException('How can there be more than one migration for a single file name?');
        }

        return $this->hydrateMigration($result[0]);
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
