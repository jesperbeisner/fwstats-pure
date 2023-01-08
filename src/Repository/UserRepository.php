<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Model\User;

final class UserRepository extends AbstractRepository
{
    public function insert(User $user): void
    {
        $sql = "INSERT INTO users (uuid, email, password, created) VALUES (:uuid, :email, :password, :created)";

        $this->database->insert($sql, [
            'uuid' => $user->uuid,
            'email' => $user->email,
            'password' => $user->password,
            'created' => $user->created->format('Y-m-d H:i:s'),
        ]);
    }

    public function findOneByEmail(string $email): ?User
    {
        $sql = "SELECT uuid, email, password, created FROM users WHERE email = :email";

        /** @var array<array{uuid: string, email: string, password: string, created: string}> $result */
        $result = $this->database->select($sql, [
            'email' => $email,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException(sprintf('How can there be more than 1 user for email "%s"', $email));
        }

        return $this->hydrateUser($result[0]);
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM users";

        $this->database->delete($sql);
    }

    /**
     * @param array{uuid: string, email: string, password: string, created: string} $row
     */
    private function hydrateUser(array $row): User
    {
        return new User(
            $row['uuid'],
            $row['email'],
            $row['password'],
            new DateTimeImmutable($row['created']),
        );
    }
}
