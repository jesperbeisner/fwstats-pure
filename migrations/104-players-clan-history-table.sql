CREATE TABLE IF NOT EXISTS players_profession_history (
    id INTEGER PRIMARY KEY,
    world TEXT NOT NULL,
    player_id INTEGER NOT NULL,
    old_profession TEXT,
    new_profession TEXT,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
