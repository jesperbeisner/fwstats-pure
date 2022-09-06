<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Dummy;

use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;

class SessionDummy implements SessionInterface
{
    /** @var mixed[] */
    private array $session = [];
    private ?User $user = null;

    public function start(): void
    {
    }

    public function destroy(): void
    {
    }

    public function get(string $key): mixed
    {
        return $this->session[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        $this->session[$key] = $value;
    }

    public function getUser(): ?User
    {
        if ($this->user !== null) {
            return $this->user;
        }

        /** @var User|null $user */
        $user = $this->session['user'] ?? null;

        $this->user = $user;

        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->session['user'] = $user;
    }
}
