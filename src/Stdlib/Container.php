<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Exception\ContainerException;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Stdlib\ContainerTest
 */
final class Container implements ContainerInterface
{
    /** @var array<class-string, object> */
    private array $services = [];

    /**
     * @param array<string, class-string<FactoryInterface>> $factories
     */
    public function __construct(
        private readonly array $factories,
    ) {
    }

    public function set(string $key, object $value): void
    {
        $this->services[$key] = $value;
    }

    public function get(string $key): object
    {
        if (array_key_exists($key, $this->services)) {
            $service = $this->services[$key];

            if (!$service instanceof $key) {
                throw new ContainerException(sprintf('Returned service is not an instance of "%s".', $key));
            }

            return $service;
        }

        if (array_key_exists($key, $this->factories)) {
            $this->services[$key] = (new $this->factories[$key]())->build($this, $key);

            $service = $this->services[$key];

            if (!$service instanceof $key) {
                throw new ContainerException(sprintf('Returned service is not an instance of "%s".', $key));
            }

            return $service;
        }

        throw new ContainerException(sprintf('Service with key "%s" does not exist in the container. Did you forget to register it in the "config.php" file?', $key));
    }

    public function has(string $key): bool
    {
        if (array_key_exists($key, $this->services)) {
            return true;
        }

        if (array_key_exists($key, $this->factories)) {
            return true;
        }

        return false;
    }
}
