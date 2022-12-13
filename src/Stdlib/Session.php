<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Exception\SessionException;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;

final class Session implements SessionInterface
{
    private ?User $user = null;

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function get(string $key): mixed
    {
        if ($this->isSessionStarted() === false) {
            $this->start();
        }

        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, mixed $value): void
    {
        if ($this->isSessionStarted() === false) {
            $this->start();
        }

        $_SESSION[$key] = $value;
    }

    public function getUser(): ?User
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $user = $this->get('user');

        if ($user === null) {
            return null;
        }

        if (is_string($user) === false) {
            throw new SessionException("Method 'get' did not return a string.");
        }

        $this->user = $this->userRepository->findOneByEmail($user);

        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->set('user', $user->email);
    }

    public function destroy(): void
    {
        if ($this->isSessionStarted()) {
            $this->user = null;
            $_SESSION = [];

            setcookie(session_id(), '', time() - 3600);
            session_destroy();
            session_write_close();
        }
    }

    private function start(): void
    {
        if ($this->isSessionStarted() === false) {
            $options = [
                'name' => 'FWSTATS',
            ];

            if (session_start($options) === false) {
                throw new SessionException('Could not start the session?! o.O');
            }
        }
    }

    private function isSessionStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }
}
