<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;

final readonly class RequestLog
{
    public function __construct(
        public ?int $id,
        public string $url,
        public string $method,
        public int $visits,
        public int $statusCode,
        public DateTimeImmutable $day,
    ) {
    }

    public static function withId(int $id, RequestLog $requestLog): RequestLog
    {
        return new RequestLog($id, $requestLog->url, $requestLog->method, $requestLog->visits, $requestLog->statusCode, $requestLog->day);
    }
}
