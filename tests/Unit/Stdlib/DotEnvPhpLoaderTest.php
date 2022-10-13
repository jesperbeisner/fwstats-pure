<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Stdlib;

use Jesperbeisner\Fwstats\Stdlib\DotEnvPhpLoader;
use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Stdlib\DotEnvPhpLoader
 */
final class DotEnvPhpLoaderTest extends TestCase
{
    public function setUp(): void
    {
        $_ENV = [];
    }

    protected function tearDown(): void
    {
        $_ENV = [];
    }

    public function test_it_will_load_all_env_vars_right(): void
    {
        DotEnvPhpLoader::load([__DIR__ . '/../../Fixtures/DotEnv/.env.valid-values.php']);

        static::assertSame('dev', $_ENV['APP_ENV']);
        static::assertSame('Hello world', $_ENV['STRING']);
        static::assertSame(123, $_ENV['INTEGER']);
        static::assertSame(123.456, $_ENV['FLOAT']);
        static::assertSame(true, $_ENV['BOOL']);
    }

    public function test_it_will_overwrite_values_from_previous_files(): void
    {
        DotEnvPhpLoader::load([
            __DIR__ . '/../../Fixtures/DotEnv/.env.valid-values.php',
            __DIR__ . '/../../Fixtures/DotEnv/.env.valid-values_second_file.php',
        ]);

        static::assertSame('prod', $_ENV['APP_ENV']);
        static::assertSame('Hello world', $_ENV['STRING']);
        static::assertSame(123, $_ENV['INTEGER']);
        static::assertSame(123.456, $_ENV['FLOAT']);
        static::assertSame(true, $_ENV['BOOL']);
    }

    public function test_it_will_throw_a_RuntimeException_when_the_file_does_not_return_an_array(): void
    {
        $dotEnvPhpFile = __DIR__ . '/../../Fixtures/DotEnv/.env.no-array-return.php';

        static::expectException(RuntimeException::class);
        static::expectExceptionMessage(sprintf('The file "%s" did not return an array.', $dotEnvPhpFile));

        DotEnvPhpLoader::load([$dotEnvPhpFile]);
    }

    public function test_it_will_throw_a_RuntimeException_when_the_array_key_is_no_string(): void
    {
        static::expectException(RuntimeException::class);
        static::expectExceptionMessage('Only string values are allowed as array keys.');

        DotEnvPhpLoader::load([__DIR__ . '/../../Fixtures/DotEnv/.env.invalid-array-keys.php']);
    }

    public function test_it_will_throw_a_RuntimeException_when_the_array_value_is_not_scalar(): void
    {
        static::expectException(RuntimeException::class);
        static::expectExceptionMessage('Only scalar values are allowed as array values.');

        DotEnvPhpLoader::load([__DIR__ . '/../../Fixtures/DotEnv/.env.invalid-array-values.php']);
    }
}
