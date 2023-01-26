<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Config;

final class ConfigRepository extends AbstractRepository
{
    public function findByKey(string $key): ?Config
    {
        $sql = "SELECT id, key, value, created FROM configs WHERE key = :key";

        /** @var null|array{id: int, key: string, value: string, created: string} $result */
        $result = $this->database->selectOne($sql, ['key' => $key]);

        if ($result === null) {
            return null;
        }

        return $this->hydrateConfig($result);
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
            'created' => (new DateTimeImmutable())->format('Y-m-d H:i:s')
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
