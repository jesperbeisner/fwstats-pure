CREATE TABLE IF NOT EXISTS users (
    uuid TEXT PRIMARY KEY,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    created DATETIME NOT NULL
);

CREATE UNIQUE INDEX users_email_unique_index ON users(email);
CREATE INDEX users_created_index ON users(created);
