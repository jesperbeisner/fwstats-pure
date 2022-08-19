CREATE TABLE IF NOT EXISTS players_active_seconds (
    id INTEGER PRIMARY KEY,
    world TEXT NOT NULL,
    player_id INTEGER NOT NULL,
    seconds INTEGER NOT NULL,
    created DATE NOT NULL DEFAULT (date('now', '-1 day'))
);
