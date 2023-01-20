<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Model\Config;

final class ConfigRepository extends AbstractRepository
{
    public function findByKey(string $key): ?Config
    {
        $sql = "SELECT id, key, value, created FROM config WHERE key = :key";

        /** @var array<array{id: int, key: string, value: string, created: string}> $result */
        $result = $this->database->select($sql, [
            'key' => $key,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException(sprintf('How can there be more than one config for the key "%s"?', $key));
        }

        return $this->hydrateConfig($result[0]);
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM config";

        $this->database->delete($sql);
    }

    /**
     * @param array{id: int, key: string, value: string, created: string} $row
     */
    private function hydrateConfig(array $row): Config
    {
        return new Config(
            id: $row['id'],
            key: $row['key'],
            value: $row['value'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
