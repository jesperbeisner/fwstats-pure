<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Command\AbstractCommand;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\EndProcessInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\StartProcessInterface;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Stdlib\ConfigTest
 */
final class Config
{
    /**
     * @param array{
     *     global: array<string, string|int|float|bool>,
     *     routes: array<array{route: string, methods: array<string>, controller: class-string<ControllerInterface>}>,
     *     startProcesses: array<class-string<StartProcessInterface>>,
     *     endProcesses: array<class-string<EndProcessInterface>>,
     *     commands: array<class-string<AbstractCommand>>,
     *     factories: array<string, class-string<FactoryInterface>>
     * } $configArray
     */
    public function __construct(
        private array $configArray
    ) {
    }

    public function set(string $key, string|int|float|bool $value): void
    {
        $this->configArray['global'][$key] = $value;
    }

    public function get(string $key): string|int|float|bool
    {
        if (array_key_exists($key, $this->configArray['global'])) {
            return $this->configArray['global'][$key];
        }

        throw new RuntimeException(sprintf('There is no config value for the key "%s".', $key));
    }

    public function getString(string $key): string
    {
        $value = $this->get($key);

        if (is_string($value)) {
            return $value;
        }

        throw new RuntimeException(sprintf('Config value for key "%s" found but it is no string.', $key));
    }

    public function getInt(string $key): int
    {
        $value = $this->get($key);

        if (is_int($value)) {
            return $value;
        }

        throw new RuntimeException(sprintf('Config value for key "%s" found but it is no int.', $key));
    }

    public function getFloat(string $key): float
    {
        $value = $this->get($key);

        if (is_float($value)) {
            return $value;
        }

        throw new RuntimeException(sprintf('Config value for key "%s" found but it is no float.', $key));
    }

    public function getBool(string $key): bool
    {
        $value = $this->get($key);

        if (is_bool($value)) {
            return $value;
        }

        throw new RuntimeException(sprintf('Config value for key "%s" found but it is no bool.', $key));
    }

    public function getAppEnv(): string
    {
        return $this->getString('app_env');
    }

    public function getRootDir(): string
    {
        return $this->getString('root_directory');
    }

    /**
     * @return array<array{route: string, methods: array<string>, controller: class-string<ControllerInterface>}>
     */
    public function getRoutes(): array
    {
        return $this->configArray['routes'];
    }

    /**
     * @return array<class-string<StartProcessInterface>>
     */
    public function getStartProcesses(): array
    {
        return $this->configArray['startProcesses'];
    }

    /**
     * @return array<class-string<EndProcessInterface>>
     */
    public function getEndProcesses(): array
    {
        return $this->configArray['endProcesses'];
    }

    /**
     * @return array<class-string<AbstractCommand>>
     */
    public function getCommands(): array
    {
        return $this->configArray['commands'];
    }

    /**
     * @return array<string, class-string<FactoryInterface>>
     */
    public function getFactories(): array
    {
        return $this->configArray['factories'];
    }
}
