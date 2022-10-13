<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Stdlib\DotEnvPhpLoaderTest
 */
final class DotEnvPhpLoader
{
    /**
     * @param string[] $dotEnvPhpFiles
     */
    public static function load(array $dotEnvPhpFiles): void
    {
        /** @var array<string, scalar> $envVars */
        $envVars = [];

        foreach ($dotEnvPhpFiles as $dotEnvPhpFile) {
            if (file_exists($dotEnvPhpFile)) {
                $dotEnvPhpFileArray = require $dotEnvPhpFile;

                if (!is_array($dotEnvPhpFileArray)) {
                    throw new RuntimeException(sprintf('The file "%s" did not return an array.', $dotEnvPhpFile));
                }

                foreach ($dotEnvPhpFileArray as $envKey => $envValue) {
                    if (!is_string($envKey)) {
                        throw new RuntimeException('Only string values are allowed as array keys.');
                    }

                    if (!is_scalar($envValue)) {
                        throw new RuntimeException('Only scalar values are allowed as array values.');
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
