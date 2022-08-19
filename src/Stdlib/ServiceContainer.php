<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Exception;
use Jesperbeisner\Fwstats\Stdlib\Exception\ContainerException;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class ServiceContainer implements ContainerInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $buildServices = [];

    /**
     * @param array<string, mixed> $services
     */
    public function __construct(
        private readonly array $services
    ) {
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->buildServices)) {
            return $this->buildServices[$id];
        }

        if (array_key_exists($id, $this->services)) {
            /** @var class-string<FactoryInterface> $factoryClassName */
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
