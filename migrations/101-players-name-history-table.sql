CREATE TABLE IF NOT EXISTS players_name_history (
    id INTEGER PRIMARY KEY,
    world TEXT NOT NULL,
    player_id INTEGER NOT NULL,
    old_name TEXT NOT NULL,
    new_name TEXT NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
