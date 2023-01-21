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
        $sql = "SELECT id, key, value, created FROM configs WHERE key = :key";

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

    public function changeDomainName(string $domainName): void
    {
        $sql = "UPDATE configs SET value = :value WHERE key = :key";

        $this->database->update($sql, [
            'value' => $domainName,
            'key' => 'domain-name',
        ]);

        $sql = "INSERT OR IGNORE INTO configs (key, value, created) VALUES (:key, :value, :created)";

        $this->database->insert($sql, [
            'key' => 'domain-name',
            'value' => $domainName,
            'created' => (new DateTimeImmutable())->format('Y-m-d')
        ]);
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM configs";

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
