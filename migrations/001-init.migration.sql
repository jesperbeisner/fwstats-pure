CREATE TABLE IF NOT EXISTS players (
    id INTEGER PRIMARY KEY,
    playerId INTEGER NOT NULL UNIQUE,
    name TEXT NOT NULL UNIQUE,
    race TEXT NOT NULL,
    clanId INTEGER NOT NULL,
    profession TEXT NOT NULL,
    xp INTEGER NOT NULL,
    soulXp INTEGER NOT NULL,
    totalXp INTEGER NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
