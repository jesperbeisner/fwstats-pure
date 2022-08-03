<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Repository\AbstractRepository;
use PDO;
use PDOStatement;
use RuntimeException;
use Throwable;

final class Database
{
    public function __construct(
        private readonly PDO $pdo,
    ) {
    }

    public function execute(string $sql): void
    {
        $this->pdo->exec($sql);
    }

    public function prepare(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * @param class-string $repositoryName
     */
    public function getRepository(string $repositoryName): AbstractRepository
    {
        if (!class_exists($repositoryName)) {
            throw new RuntimeException("The given class name '$repositoryName' does not exist.");
        }

        try {
            $repository = new $repositoryName($this);
        } catch (Throwable $e) {
            throw new RuntimeException("Could not create the given class. Are you sure '$repositoryName' is an instance of AbstractRepository?");
        }

        return $repository;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
