<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Interface\DatabaseInterface;
use Jesperbeisner\Fwstats\Interface\LoggerInterface;
use PDO;

final class Database implements DatabaseInterface
{
    private bool $transactionStarted = false;

    public function __construct(
        private readonly PDO $pdo,
        private readonly ?LoggerInterface $logger,
    ) {
    }

    public function execute(string $sql, array $params = []): void
    {
        $this->log($sql);

        $this->pdo->prepare($sql)->execute($params);
    }

    public function select(string $sql, array $params = []): array
    {
        $this->log($sql);

        $statement = $this->pdo->prepare($sql);

        $statement->execute($params);

        return $statement->fetchAll();
    }

    public function insert(string $sql, array $params = []): int
    {
        $this->log($sql);

        $this->pdo->prepare($sql)->execute($params);

        return (int) $this->pdo->lastInsertId();
    }

    public function update(string $sql, array $params = []): void
    {
        $this->log($sql);

        $this->pdo->prepare($sql)->execute($params);
    }

    public function delete(string $sql, array $params = []): void
    {
        $this->log($sql);

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

    private function log(string $sql): void
    {
        $this->logger?->info(sprintf('SQL query executed: [%s]', preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $sql)));
    }
}
