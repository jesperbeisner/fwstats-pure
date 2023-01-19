<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS clans_deleted_history (id INTEGER PRIMARY KEY, world TEXT NOT NULL, clan_id INTEGER NOT NULL, shortcut TEXT NOT NULL, name TEXT NOT NULL, leader_id INTEGER NOT NULL, co_leader_id INTEGER NOT NULL, diplomat_id INTEGER NOT NULL, war_points INTEGER NOT NULL, created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)",
];
