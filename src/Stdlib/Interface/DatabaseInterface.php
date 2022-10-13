<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

interface DatabaseInterface
{
    /**
     * @param array<string, string|int> $params
     */
    public function execute(string $sql, array $params = []): void;

    /**
     * @param array<string, string|int> $params
     */
    public function select(string $sql, array $params = []): array;

    /**
     * @param array<string, string|int> $params
     */
    public function insert(string $sql, array $params = []): void;

    /**
     * @param array<string, string|int> $params
     */
    public function update(string $sql, array $params = []): void;

    /**
     * @param array<string, string|int> $params
     */
    public function delete(string $sql, array $params = []): void;

    public function beginTransaction(): void;

    public function commitTransaction(): void;

    public function rollbackTransaction(): void;
}