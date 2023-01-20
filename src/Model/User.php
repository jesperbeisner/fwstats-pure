<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Model;

use DateTimeImmutable;

final readonly class User
{
    public function __construct(
        public ?int $id,
        public string $uuid,
        public string $username,
        public string $password,
        public string $token,
        public DateTimeImmutable $created
    ) {
    }

    public static function withId(int $id, User $user): User
    {
        return new User($id, $user->uuid, $user->username, $user->password, $user->token, $user->created);
    }
}
