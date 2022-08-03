<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Exception;
use Jesperbeisner\Fwstats\Stdlib\Exception\ContainerException;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

final class ServiceContainer implements ContainerInterface
{
    private array $services;
    private array $buildServices = [];

    public function __construct(array $services)
    {
        $this->services = $services;
    }

    public function get(string $id)
    {
        if (array_key_exists($id, $this->buildServices)) {
            return $this->buildServices[$id];
        }

        if (array_key_exists($id, $this->services)) {
            $factoryClassName = $this->services[$id];

            try {
                $service = (new $factoryClassName())($this, $id);
            } catch (Exception $e) {
                throw new ContainerException($e->getMessage());
            }

            $this->buildServices[$id] = $service;

            return $service;
        }

        throw new NotFoundException(sprintf("Service '%s' does not exist in the ServiceManager. Did you forget to register it?", $id));
    }

    public function set(string $identifier, mixed $service): void
    {
        $this->buildServices[$identifier] = $service;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
