CREATE TABLE IF NOT EXISTS clans_name_history (
    id INTEGER PRIMARY KEY,
    world TEXT NOT NULL,
    clan_id INTEGER NOT NULL,
    old_shortcut TEXT NOT NULL,
    new_shortcut TEXT NOT NULL,
    old_name TEXT NOT NULL,
    new_name TEXT NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
