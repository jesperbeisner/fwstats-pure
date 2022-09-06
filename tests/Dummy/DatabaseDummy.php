<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Dummy;

use Jesperbeisner\Fwstats\Stdlib\Interface\DatabaseInterface;

class DatabaseDummy implements DatabaseInterface
{
    /** @var mixed[]|null */
    public ?array $fetchOneResult = null;

    /**
     * @param mixed[]|null $fetchOneResult
     */
    public function setFetchOneResult(?array $fetchOneResult): void
    {
        $this->fetchOneResult = $fetchOneResult;
    }

    public function deleteAll(string $tableName): void
    {
    }

    public function insert(string $sql, array $params = []): void
    {
    }

    public function fetchOne(string $sql, array $params = []): ?array
    {
        return $this->fetchOneResult;
    }
}
