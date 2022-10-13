<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;

final class Config
{
    /** @var mixed[] */
    private array $config;

    public function __construct(string $configFile)
    {
        if (!file_exists($configFile)) {
            throw new RuntimeException(sprintf('The provided config file "%s" does not exist.', $configFile));
        }

        $configArray = require $configFile;

        if (!is_array($configArray)) {
            throw new RuntimeException(sprintf('The provided config file "%s" did not return an array.', $configFile));
        }

        $this->config = $configArray;
    }

    public function set(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    public function get(string $key): mixed
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        throw new RuntimeException(sprintf('There is no config value for the key "%s".', $key));
    }

    public function getAppEnv(): string
    {
        if (!array_key_exists('app_env', $this->config)) {
            throw new RuntimeException('There is no config value for the "app_env" key.');
        }

        $appEnv = $this->config['app_env'];

        if (!is_string($appEnv)) {
            throw new RuntimeException('The config value for the "app_env" key is not of type string.');
        }

        return $appEnv;
    }

    public function getRootDir(): string
    {
        if (!array_key_exists('root_dir', $this->config)) {
            throw new RuntimeException('There is no config value for the "root_dir" key.');
        }

        $rootDir = $this->config['root_dir'];

        if (!is_string($rootDir)) {
            throw new RuntimeException('The config value for the "root_dir" key is not of type string.');
        }

        return $rootDir;
    }
}
