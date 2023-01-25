<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Dummy;

use Jesperbeisner\Fwstats\Interface\DatabaseInterface;

final class DatabaseDummy implements DatabaseInterface
{
    /** @var array<mixed> */
    private array $selectReturn = [];

    /**
     * @param array<mixed> $selectReturn
     */
    public function setSelectReturn(array $selectReturn): void
    {
        $this->selectReturn = $selectReturn;
    }

    public function execute(string $sql, array $params = []): void
    {
    }

    public function select(string $sql, array $params = []): array
    {
        return $this->selectReturn;
    }

    public function insert(string $sql, array $params = []): int
    {
        return 1;
    }

    public function update(string $sql, array $params = []): void
    {
    }

    public function delete(string $sql, array $params = []): void
    {
    }

    public function beginTransaction(): void
    {
    }

    public function commitTransaction(): void
    {
    }

    public function rollbackTransaction(): void
    {
    }
}
