<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS cronjobs (id INTEGER PRIMARY KEY, created DATETIME NOT NULL)",
    "CREATE INDEX IF NOT EXISTS cronjobs_created_index ON cronjobs(created)",
];
