<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Interface\DatabaseInterface;
use PDO;

final class Database implements DatabaseInterface
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function insert(string $sql, array $params = []): void
    {
        $this->pdo->prepare($sql)->execute($params);
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        /** @var mixed[]|false $data */
        $data = $stmt->fetch();

        if ($data === false) {
            return null;
        }

        return $data;
    }

    public function deleteAll(string $tableName): void
    {
        $this->pdo->prepare("DELETE FROM $tableName")->execute();
    }
}
