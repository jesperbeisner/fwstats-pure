<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Log;

final class LogRepository extends AbstractRepository
{
    public function insert(Log $log): void
    {
        $sql = "INSERT INTO logs (url, created) VALUES (:url, :created)";

        $this->database->insert($sql, [
            'url' => $log->url,
            'created' => $log->created->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return Log[]
     */
    public function findLast250Logs(): array
    {
        $sql = "SELECT * FROM logs ORDER BY id DESC LIMIT 250";

        $result = $this->database->select($sql);

        $logs = [];
        foreach ($result as $row) {
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
