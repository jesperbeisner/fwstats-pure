<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS players_xp_history (id INTEGER PRIMARY KEY, world TEXT NOT NULL, player_id INTEGER NOT NULL, start_xp INTEGER NOT NULL, end_xp INTEGER NOT NULL, day DATETIME NOT NULL)",
    "CREATE UNIQUE INDEX IF NOT EXISTS players_xp_history_world_player_id_day_unique_index ON players_xp_history(world, player_id, day)",
];
