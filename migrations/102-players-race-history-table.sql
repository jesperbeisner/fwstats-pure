CREATE TABLE IF NOT EXISTS players_race_history (
    id INTEGER PRIMARY KEY,
    world TEXT NOT NULL,
    player_id INTEGER NOT NULL,
    old_race TEXT NOT NULL,
    new_race TEXT NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
