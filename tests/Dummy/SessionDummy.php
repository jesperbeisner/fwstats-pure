<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Dummy;

use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Model\User;

final class SessionDummy implements SessionInterface
{
    /** @var mixed[] */
    private array $session = [];
    private ?User $user = null;

    public function destroy(): void
    {
    }

    public function get(string $key): string|int|float|bool|null
    {
        return $this->session[$key] ?? null;
    }

    public function set(string $key, string|int|float|bool $value): void
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

    public function start(): void
    {
        // TODO: Implement start() method.
    }

    public function unset(string $key): void
    {
        // TODO: Implement unset() method.
    }
}
