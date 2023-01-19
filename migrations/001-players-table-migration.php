<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS players (id INTEGER PRIMARY KEY, world TEXT NOT NULL, player_id INTEGER NOT NULL, name TEXT NOT NULL, race TEXT NOT NULL, clan_id INTEGER, profession TEXT, xp INTEGER NOT NULL, soul_xp INTEGER NOT NULL, total_xp INTEGER NOT NULL, created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)",
    "CREATE UNIQUE INDEX IF NOT EXISTS players_world_player_id_unique_index ON players(world, player_id)",
];
