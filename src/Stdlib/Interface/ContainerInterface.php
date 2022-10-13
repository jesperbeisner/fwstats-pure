<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

interface ContainerInterface
{
    public function set(string $serviceId, mixed $service): void;

    public function get(string $serviceId): mixed;

    public function has(string $serviceId): bool;
}
