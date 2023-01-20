<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Interface;

interface FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): object;
}
