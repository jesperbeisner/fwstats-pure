CREATE TABLE IF NOT EXISTS players_clan_history (
    id INTEGER PRIMARY KEY,
    world TEXT NOT NULL,
    player_id INTEGER NOT NULL,
    old_clan_id INTEGER,
    new_clan_id INTEGER,
    old_shortcut TEXT,
    new_shortcut TEXT,
    old_name TEXT,
    new_name TEXT,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
