<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS players_active_seconds (id INTEGER PRIMARY KEY, world TEXT NOT NULL, player_id INTEGER NOT NULL, seconds INTEGER NOT NULL, created DATE NOT NULL)",
    "CREATE UNIQUE INDEX IF NOT EXISTS players_active_seconds_world_player_id_created_unique_index ON players_active_seconds(world, player_id, created)",
];
