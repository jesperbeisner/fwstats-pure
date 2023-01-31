<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS players_created_history (id INTEGER PRIMARY KEY, world TEXT NOT NULL, player_id INTEGER NOT NULL, name TEXT NOT NULL, created DATETIME NOT NULL)",
];
