<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

interface ActionResultInterface
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    public function isSuccess(): bool;

    /**
     * @return mixed[]
     */
    public function getData(): array;

    public function getMessage(): string;
}
