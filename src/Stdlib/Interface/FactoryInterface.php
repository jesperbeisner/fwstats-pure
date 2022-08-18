<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

use Psr\Container\ContainerInterface;

interface FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): mixed;
}
