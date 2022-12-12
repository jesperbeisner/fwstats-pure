<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Stdlib\DotEnvLoaderTest
 */
final class DotEnvLoader
{
    /**
     * @param array<string> $dotEnvFiles
     */
    public static function load(array $dotEnvFiles): void
    {
        /** @var array<string, scalar> $envVars */
        $envVars = [];

        foreach ($dotEnvFiles as $dotEnvFile) {
            if (file_exists($dotEnvFile)) {
                $dotEnvFileArray = require $dotEnvFile;

                if (!is_array($dotEnvFileArray)) {
                    throw new RuntimeException(sprintf('The file "%s" did not return an array.', $dotEnvFile));
                }

                foreach ($dotEnvFileArray as $envKey => $envValue) {
                    if (!is_string($envKey)) {
                        throw new RuntimeException(sprintf('Only string values are allowed as array keys in file "%s".', $dotEnvFile));
                    }

                    if (!is_scalar($envValue)) {
                        throw new RuntimeException(sprintf('Only scalar values are allowed as array values in file "%s".', $dotEnvFile));
                    }

                    $envVars[$envKey] = $envValue;
                }
            }
        }

        foreach ($envVars as $envKey => $envValue) {
            $_ENV[$envKey] = $envValue;
        }
    }
}
