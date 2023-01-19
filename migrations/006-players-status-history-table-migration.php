<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS players_status_history (id INTEGER PRIMARY KEY, world TEXT NOT NULL, player_id INTEGER NOT NULL, name TEXT NOT NULL, status TEXT NOT NULL, created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, deleted DATETIME NULL)",
];
