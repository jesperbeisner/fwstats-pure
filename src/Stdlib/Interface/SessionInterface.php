<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

use Jesperbeisner\Fwstats\Model\User;

interface SessionInterface
{
    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;

    public function getUser(): ?User;

    public function setUser(User $user): void;

    public function destroy(): void;
}
