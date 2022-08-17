<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use PDO;

abstract class AbstractRepository
{
    public function __construct(
        protected readonly PDO $pdo,
    ) {
    }
}
