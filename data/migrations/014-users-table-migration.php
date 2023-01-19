<?php

declare(strict_types=1);

return [
    "CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, uuid TEXT NOT NULL, email TEXT NOT NULL, password TEXT NOT NULL, token TEXT NOT NULL, created DATETIME NOT NULL)",
    "CREATE UNIQUE INDEX IF NOT EXISTS users_email_unique_index ON users(email)",
    "CREATE UNIQUE INDEX IF NOT EXISTS users_uuid_unique_index ON users(uuid)",
    "CREATE UNIQUE INDEX IF NOT EXISTS users_token_unique_index ON users(token)",
    "CREATE INDEX IF NOT EXISTS users_created_index ON users(created)",
];
