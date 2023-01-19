<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS achievements (id INTEGER PRIMARY KEY, world TEXT NOT NULL, player_id INTEGER NOT NULL, fields_walked INTEGER NOT NULL, fields_elixir INTEGER NOT NULL, fields_run INTEGER NOT NULL, fields_run_fast INTEGER NOT NULL, npc_kills_gold INTEGER NOT NULL, normal_npc_killed INTEGER NOT NULL, phase_npc_killed INTEGER NOT NULL, aggressive_npc_killed INTEGER NOT NULL, invasion_npc_killed INTEGER NOT NULL, unique_npc_killed INTEGER NOT NULL, group_npc_killed INTEGER NOT NULL, soul_stones_gained INTEGER NOT NULL, created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)",
    "CREATE UNIQUE INDEX IF NOT EXISTS achievements_world_player_id_unique_index ON achievements(world, player_id)",
];
