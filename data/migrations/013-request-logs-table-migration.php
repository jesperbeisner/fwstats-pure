<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS request_logs (id INTEGER PRIMARY KEY, url TEXT NOT NULL, method TEXT NOT NULL, visits INTEGER NOT NULL, status_code INTEGER NOT NULL, day DATETIME NOT NULL)",
    "CREATE UNIQUE INDEX IF NOT EXISTS request_logs_url_method_status_code_day_unique_index ON request_logs(url, method, status_code, day)",
];
