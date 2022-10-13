<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

interface FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): mixed;
}
