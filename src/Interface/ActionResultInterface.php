<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

interface ActionResultInterface
{
    public function isSuccess(): bool;

    public function getMessage(): string;

    /**
     * @return array<string, mixed>
     */
    public function getData(): array;
}
