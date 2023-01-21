<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Model\User;

final class UserRepository extends AbstractRepository
{
    public function insert(User $user): User
    {
        $sql = "INSERT INTO users (uuid, username, password, token, created) VALUES (:uuid, :username, :password, :token, :created)";

        $id = $this->database->insert($sql, [
            'uuid' => $user->uuid,
            'username' => $user->username,
            'password' => $user->password,
            'token' => $user->token,
            'created' => $user->created->format('Y-m-d H:i:s'),
        ]);

        return User::withId($id, $user);
    }

    public function findOneByUsername(string $username): ?User
    {
        $sql = "SELECT id, uuid, username, password, token, created FROM users WHERE username = :username";

        /** @var array<array{id: int, uuid: string, username: string, password: string, token: string, created: string}> $result */
        $result = $this->database->select($sql, [
            'username' => $username,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException(sprintf('How can there be more than one user for username "%s"', $username));
        }

        return $this->hydrateUser($result[0]);
    }

    public function findOneByToken(string $token): ?User
    {
        $sql = "SELECT id, uuid, username, password, token, created FROM users WHERE token = :token";

        /** @var array<array{id: int, uuid: string, username: string, password: string, token: string, created: string}> $result */
        $result = $this->database->select($sql, [
            'token' => $token,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException(sprintf('How can there be more than one user for token "%s"', $token));
        }

        return $this->hydrateUser($result[0]);
    }

    public function changePassword(User $user, string $hashedPassword): void
    {
        $sql = "UPDATE users SET password = :password WHERE id = :id";

        if ($user->id === null) {
            throw new RuntimeException(sprintf('The user with username "%s" has no id, how is this possible?', $user->username));
        }

        $this->database->execute($sql, [
            'password' => $hashedPassword,
            'id' => $user->id,
        ]);
    }

    public function changeToken(User $user, string $token): void
    {
        $sql = "UPDATE users SET token = :token WHERE id = :id";

        if ($user->id === null) {
            throw new RuntimeException(sprintf('The user with username "%s" has no id, how is this possible?', $user->username));
        }

        $this->database->execute($sql, [
            'token' => $token,
            'id' => $user->id,
        ]);
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM users";

        $this->database->delete($sql);
    }

    /**
     * @param array{id: int, uuid: string, username: string, password: string, token: string, created: string} $row
     */
    private function hydrateUser(array $row): User
    {
        return new User(
            $row['id'],
            $row['uuid'],
            $row['username'],
            $row['password'],
            $row['token'],
            new DateTimeImmutable($row['created']),
        );
    }
}
