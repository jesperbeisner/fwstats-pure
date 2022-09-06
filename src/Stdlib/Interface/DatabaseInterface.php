<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

interface DatabaseInterface
{
    /**
     * @param array<string, string|int> $params
     */
    public function insert(string $sql, array $params = []): void;

    /**
     * @param array<string, string|int> $params
     * @return mixed[]|null
     */
    public function fetchOne(string $sql, array $params = []): ?array;

    public function deleteAll(string $tableName): void;
}
