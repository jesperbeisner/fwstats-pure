<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\RequestLog;

final class RequestLogRepository extends AbstractRepository
{
    public function insert(RequestLog $requestLog): void
    {
        $sql = "INSERT INTO request_logs (url, method, visits, status_code, day) VALUES (:url, :method, :visits, :statusCode, :created)";

        $this->database->insert($sql, [
            'url' => $requestLog->url,
            'method' => $requestLog->method,
            'visits' => $requestLog->visits,
            'status_code' => $requestLog->statusCode,
            'day' => $requestLog->day->format('Y-m-d'),
        ]);
    }

    public function log(string $url, string $method, int $statusCode, DateTimeImmutable $day): void
    {
        $sql = <<<SQL
            UPDATE request_logs
            SET visits = visits + 1
            WHERE url = :url AND method = :method AND status_code = :statusCode AND day = :day
        SQL;

        $this->database->update($sql, [
            'url' => $url,
            'method' => $method,
            'statusCode' => $statusCode,
            'day' => $day->format('Y-m-d')
        ]);

        $sql = <<<SQL
            INSERT OR IGNORE INTO request_logs (url, method, visits, status_code, day)
            VALUES (:url, :method, :visits, :statusCode, :day)
        SQL;

        $this->database->insert($sql, [
            'url' => $url,
            'method' => $method,
            'visits' => 1,
            'statusCode' => $statusCode,
            'day' => $day->format('Y-m-d'),
        ]);
    }

    /**
     * @return array<RequestLog>
     */
    public function findLogsForDay(DateTimeImmutable $day): array
    {
        $sql = "SELECT id, url, method, visits, status_code, day FROM request_logs WHERE day = :day ORDER BY visits DESC";

        $result = $this->database->select($sql, [
            'day' => $day->format('Y-m-d'),
        ]);

        $requestLogs = [];
        foreach ($result as $row) {
            /** @var array{id: int, url: string, method: string, visits: int, status_code: int, day: string} $row */
            $requestLogs[] = $this->hydrateRequestLog($row);
        }

        return $requestLogs;
    }

    /**
     * @param array{id: int, url: string, method: string, visits: int, status_code: int, day: string} $row
     */
    private function hydrateRequestLog(array $row): RequestLog
    {
        return new RequestLog(
            $row['id'],
            $row['url'],
            $row['method'],
            $row['visits'],
            $row['status_code'],
            new DateTimeImmutable($row['day']),
        );
    }
}
