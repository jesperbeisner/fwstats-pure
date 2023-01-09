<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Model\User;

final class UserRepository extends AbstractRepository
{
    public function insert(User $user): User
    {
        $sql = "INSERT INTO users (uuid, email, password, token, created) VALUES (:uuid, :email, :password, :token, :created)";

        $id = $this->database->insert($sql, [
            'uuid' => $user->uuid,
            'email' => $user->email,
            'password' => $user->password,
            'token' => $user->token,
            'created' => $user->created->format('Y-m-d H:i:s'),
        ]);

        return User::withId($id, $user);
    }

    public function findOneByEmail(string $email): ?User
    {
        $sql = "SELECT id, uuid, email, password, token, created FROM users WHERE email = :email";

        /** @var array<array{id: int, uuid: string, email: string, password: string, token: string, created: string}> $result */
        $result = $this->database->select($sql, [
            'email' => $email,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException(sprintf('How can there be more than one user for email "%s"', $email));
        }

        return $this->hydrateUser($result[0]);
    }

    public function findOneByToken(string $token): ?User
    {
        $sql = "SELECT id, uuid, email, password, token, created FROM users WHERE token = :token";

        /** @var array<array{id: int, uuid: string, email: string, password: string, token: string, created: string}> $result */
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

    public function deleteAll(): void
    {
        $sql = "DELETE FROM users";

        $this->database->delete($sql);
    }

    /**
     * @param array{id: int, uuid: string, email: string, password: string, token: string, created: string} $row
     */
    private function hydrateUser(array $row): User
    {
        return new User(
            $row['id'],
            $row['uuid'],
            $row['email'],
            $row['password'],
            $row['token'],
            new DateTimeImmutable($row['created']),
        );
    }
}
