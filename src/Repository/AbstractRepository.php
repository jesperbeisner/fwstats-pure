<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Stdlib\Database;
use RuntimeException;

abstract class AbstractRepository
{
    protected const TABLE_NAME = null;
    protected const PRIMARY_KEY_NAME = 'id';

    public function __construct(
        protected readonly Database $database,
    ) {
    }

    public function find(int $id): array
    {
        $this->checkTableName();

        $statement = $this->database->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE " . self::PRIMARY_KEY_NAME . " = :id");
        $statement->execute([self::PRIMARY_KEY_NAME => $id]);

        /** @var mixed[] $result */
        $result = $statement->fetchAll();

        return $result;
    }

    public function findAll(): array
    {
        $this->checkTableName();

        $statement = $this->database->getPdo()->query("SELECT * FROM " . self::TABLE_NAME);

        return $statement->fetchAll();
    }

    private function checkTableName(): void
    {
        if (self::TABLE_NAME === null) {
            throw new RuntimeException("TABLE_NAME is still null in your repository. Did you forget to set the constant?");
        }
    }
}
