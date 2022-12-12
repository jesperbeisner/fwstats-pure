<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;

final class Config
{
    /**
     * @param array<string, string|int|float|bool> $configArray
     */
    public function __construct(
        private array $configArray
    ) {
    }

    public function set(string $key, string|int|float|bool $value): void
    {
        $this->configArray[$key] = $value;
    }

    public function get(string $key): string|int|float|bool
    {
        if (array_key_exists($key, $this->configArray)) {
            return $this->configArray[$key];
        }

        throw new RuntimeException(sprintf('There is no config value for the key "%s".', $key));
    }

    public function getAppEnv(): string
    {
        if (!array_key_exists('app_env', $this->configArray)) {
            throw new RuntimeException('There is no config value for the "app_env" key.');
        }

        $appEnv = $this->configArray['app_env'];

        if (!is_string($appEnv)) {
            throw new RuntimeException('The config value for the "app_env" key is not of type string.');
        }

        return $appEnv;
    }

    public function getRootDir(): string
    {
        if (!array_key_exists('root_dir', $this->configArray)) {
            throw new RuntimeException('There is no config value for the "root_dir" key.');
        }

        $rootDir = $this->configArray['root_dir'];

        if (!is_string($rootDir)) {
            throw new RuntimeException('The config value for the "root_dir" key is not of type string.');
        }

        return $rootDir;
    }
}
