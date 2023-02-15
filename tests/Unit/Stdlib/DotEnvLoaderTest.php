<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Stdlib;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Stdlib\DotEnvLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DotEnvLoader::class)]
final class DotEnvLoaderTest extends TestCase
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
        DotEnvLoader::load([__DIR__ . '/../_Fixtures/DotEnv/.env.valid-values.php']);

        self::assertSame('dev', $_ENV['APP_ENV']);
        self::assertSame('Hello world', $_ENV['STRING']);
        self::assertSame(123, $_ENV['INTEGER']);
        self::assertSame(123.456, $_ENV['FLOAT']);
        self::assertTrue($_ENV['BOOL']);
    }

    public function test_it_will_overwrite_values_from_previous_files(): void
    {
        DotEnvLoader::load([
            __DIR__ . '/../_Fixtures/DotEnv/.env.valid-values.php',
            __DIR__ . '/../_Fixtures/DotEnv/.env.valid-values_second_file.php',
        ]);

        self::assertSame('prod', $_ENV['APP_ENV']);
        self::assertSame('Hello world', $_ENV['STRING']);
        self::assertSame(123, $_ENV['INTEGER']);
        self::assertSame(123.456, $_ENV['FLOAT']);
        self::assertTrue($_ENV['BOOL']);
    }

    public function test_it_will_throw_a_RuntimeException_when_the_file_does_not_return_an_array(): void
    {
        $dotEnvFile = __DIR__ . '/../_Fixtures/DotEnv/.env.no-array-return.php';

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage(sprintf('The file "%s" did not return an array.', $dotEnvFile));

        DotEnvLoader::load([$dotEnvFile]);
    }

    public function test_it_will_throw_a_RuntimeException_when_the_array_key_is_no_string(): void
    {
        $dotEnvFile = __DIR__ . '/../_Fixtures/DotEnv/.env.invalid-array-keys.php';

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage(sprintf('Only string values are allowed as array keys in file "%s".', $dotEnvFile));

        DotEnvLoader::load([$dotEnvFile]);
    }

    public function test_it_will_throw_a_RuntimeException_when_the_array_value_is_not_scalar(): void
    {
        $dotEnvFile = __DIR__ . '/../_Fixtures/DotEnv/.env.invalid-array-values.php';

        self::expectException(RuntimeException::class);
        self::expectExceptionMessage(sprintf('Only scalar values are allowed as array values in file "%s".', $dotEnvFile));

        DotEnvLoader::load([$dotEnvFile]);
    }
}
