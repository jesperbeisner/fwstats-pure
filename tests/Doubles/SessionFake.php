<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Doubles;

use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Model\User;

final class SessionFake implements SessionInterface
{
    private ?User $user = null;

    /** @var array<string, mixed> */
    private array $session = [];

    /** @var array<string, array<string>> */
    private array $flashMessages = [];

    public function start(): void
    {
    }

    public function get(string $key): string|int|float|bool|null
    {
        $result = $this->session[$key] ?? null;

        if (is_string($result) || is_int($result) || is_float($result) || is_bool($result) || is_null($result)) {
            return $result;
        }

        throw new RuntimeException('How did this happen?');
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

    public function setFlash(FlashEnum $flashEnum, string $message): void
    {
        $this->flashMessages[$flashEnum->value][] = $message;
    }

    public function getFlash(FlashEnum $flashEnum): array
    {
        return $this->flashMessages[$flashEnum->value] ?? [];
    }

    public function unset(string $key): void
    {
        unset($this->session[$key]);
    }

    public function destroy(): void
    {
        $this->session = [];
        $this->user = null;
    }
}
