<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use PDO;

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
}
