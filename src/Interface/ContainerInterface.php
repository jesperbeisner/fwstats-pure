<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

interface ContainerInterface
{
    /**
     * @template T of object
     * @param class-string<T> $key
     * @param T $value
     */
    public function set(string $key, object $value): void;

    /**
     * @template T of object
     * @param class-string<T> $key
     * @return T
     */
    public function get(string $key): object;

    /**
     * @param class-string $key
     */
    public function has(string $key): bool;
}
