<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Stdlib\Interface\DatabaseInterface;

final class UserRepository
{
    private string $table = 'users';

    public function __construct(
        private readonly DatabaseInterface $database,
    ) {
    }

    public function insert(User $user): void
    {
        $sql = "INSERT INTO $this->table (uuid, email, password, created) VALUES (:uuid, :email, :password, :created)";

        $this->database->insert($sql, [
            'uuid' => $user->uuid,
            'email' => $user->email,
            'password' => $user->password,
            'created' => $user->created->format('Y-m-d H:i:s'),
        ]);
    }

    public function findOneByEmail(string $email): ?User
    {
        $sql = "SELECT uuid, email, password, created FROM $this->table WHERE email = :email";

        /** @var array{uuid: string, email: string, password: string, created: string}|null $userData */
        $userData = $this->database->fetchOne($sql, ['email' => $email]);

        if ($userData === null) {
            return null;
        }

        return $this->hydrateUser($userData);
    }

    public function deleteAll(): void
    {
        $this->database->deleteAll($this->table);
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
