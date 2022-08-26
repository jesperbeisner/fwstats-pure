<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Log;

final class LogRepository extends AbstractRepository
{
    private string $table = 'logs';

    public function insert(Log $log): void
    {
        $sql = "INSERT INTO $this->table (url, created) VALUES (:url, :created)";

        $this->pdo->prepare($sql)->execute([
            'url' => $log->url,
            'created' => $log->created->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return Log[]
     */
    public function findLast250Logs(): array
    {
        $sql = "SELECT * FROM $this->table ORDER BY id DESC LIMIT 250";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $logs = [];
        while (false !== $row = $stmt->fetch()) {
            /** @var array{id: int, url: string, created: string} $row */
            $logs[] = $this->hydrateLog($row);
        }

        return $logs;
    }

    /**
     * @param array{id: int, url: string, created: string} $row
     */
    private function hydrateLog(array $row): Log
    {
        return new Log(
            $row['url'],
            new DateTimeImmutable($row['created']),
        );
    }
}
