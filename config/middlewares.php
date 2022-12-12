<?php

declare(strict_types=1);

use Jesperbeisner\Fwstats\Middleware;

return [
    Middleware\ExceptionHandlerMiddleware::class,
    Middleware\DatabaseRequestLoggerMiddleware::class,
];
