<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Stdlib\Interface\DatabaseInterface;
use PDO;

final class Database implements DatabaseInterface
{
    private bool $transactionStarted = false;

    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function execute(string $sql, array $params = []): void
    {
        $this->pdo->prepare($sql)->execute($params);
    }

    public function select(string $sql, array $params = []): array
    {
        $statement = $this->pdo->prepare($sql);

        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function insert(string $sql, array $params = []): void
    {
        $this->pdo->prepare($sql)->execute($params);
    }

    public function update(string $sql, array $params = []): void
    {
        $this->pdo->prepare($sql)->execute($params);
    }

    public function delete(string $sql, array $params = []): void
    {
        $this->pdo->prepare($sql)->execute($params);
    }

    public function beginTransaction(): void
    {
        if ($this->transactionStarted === true) {
            throw new DatabaseException('The transaction was already started. You can call "beginTransaction" only once per transaction.');
        }

        $this->pdo->beginTransaction();

        $this->transactionStarted = true;
    }

    public function commitTransaction(): void
    {
        if ($this->transactionStarted === false) {
            throw new DatabaseException('No transaction was started that you can commit.');
        }

        $this->pdo->commit();

        $this->transactionStarted = false;
    }

    public function rollbackTransaction(): void
    {
        if ($this->transactionStarted === false) {
            throw new DatabaseException('No transaction was started that you can rollback.');
        }

        $this->pdo->rollBack();

        $this->transactionStarted = false;
    }
}
