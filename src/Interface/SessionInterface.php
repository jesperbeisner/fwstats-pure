<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Model\User;

interface SessionInterface
{
    public function start(): void;

    public function get(string $key): string|int|float|bool|null;

    public function set(string $key, string|int|float|bool $value): void;

    public function getUser(): ?User;

    public function setUser(User $user): void;

    public function setFlash(FlashEnum $flashEnum, string $message): void;

    /**
     * @return array<string>
     */
    public function getFlash(FlashEnum $flashEnum): array;

    public function unset(string $key): void;

    public function destroy(): void;
}
