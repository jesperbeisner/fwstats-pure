<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Exception;
use Jesperbeisner\Fwstats\Stdlib\Exception\ContainerException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class Container implements ContainerInterface
{
    /** @var array<string, class-string<FactoryInterface>>  */
    private array $serviceFactories;

    /** @var array<string, mixed> */
    private array $services = [];

    public function __construct(string $servicesFile)
    {
        if (!file_exists($servicesFile)) {
            throw new ContainerException(sprintf('The provided services file "%s" does not exist.', $servicesFile));
        }

        $serviceFactories = require $servicesFile;

        if (!is_array($serviceFactories)) {
            throw new ContainerException(sprintf('The provided services file "%s" did not return an array.', $servicesFile));
        }

        $this->serviceFactories = $serviceFactories;
    }

    public function set(string $serviceId, mixed $service): void
    {
        $this->services[$serviceId] = $service;
    }

    public function get(string $serviceId): mixed
    {
        if (array_key_exists($serviceId, $this->services)) {
            return $this->services[$serviceId];
        }

        if (array_key_exists($serviceId, $this->serviceFactories)) {
            /** @var class-string<FactoryInterface> $factoryClassName */
            $factoryClassName = $this->serviceFactories[$serviceId];

            try {
                $service = (new $factoryClassName())->build($this, $serviceId);
            } catch (Exception $e) {
                throw new ContainerException($e->getMessage());
            }

            $this->services[$serviceId] = $service;

            return $service;
        }

        throw new ContainerException(sprintf('Service with id "%s" does not exist in the Container. Did you forget to register it?', $serviceId));
    }

    public function has(string $serviceId): bool
    {
        if (array_key_exists($serviceId, $this->services)) {
            return true;
        }

        if (array_key_exists($serviceId, $this->serviceFactories)) {
            return true;
        }

        return false;
    }
}
