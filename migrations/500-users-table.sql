CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    uuid TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    token TEXT NOT NULL,
    created DATETIME NOT NULL
);

CREATE UNIQUE INDEX users_email_unique_index ON users(email);
CREATE UNIQUE INDEX users_uuid_unique_index ON users(uuid);
CREATE UNIQUE INDEX users_token_unique_index ON users(token);
CREATE INDEX users_created_index ON users(created);
