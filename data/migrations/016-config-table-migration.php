<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS configs (id INTEGER PRIMARY KEY, key TEXT NOT NULL, value TEXT NOT NULL, created DATETIME NOT NULL)",
    "CREATE UNIQUE INDEX IF NOT EXISTS configs_key_unique_index ON configs(key)",
];
