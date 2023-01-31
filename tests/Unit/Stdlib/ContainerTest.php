<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Stdlib;

use Jesperbeisner\Fwstats\Exception\ContainerException;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Container;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Jesperbeisner\Fwstats\Stdlib\Container
 */
final class ContainerTest extends TestCase
{
    public function test_get_throws_a_ContainerException_when_the_service_does_not_exist(): void
    {
        $container = new Container([]);

        self::expectException(ContainerException::class);
        self::expectExceptionMessage('Service with key "stdClass" does not exist in the container. Did you forget to register it in the "config.php" file?');

        $container->get(stdClass::class);
    }

    public function test_get_throws_a_ContainerException_when_the_service_is_not_an_instance_of_key(): void
    {
        $container = new Container([]);
        $container->set(stdClass::class, new Container([]));

        self::expectException(ContainerException::class);
        self::expectExceptionMessage('Returned service is not an instance of "stdClass".');

        $container->get(stdClass::class);
    }

    public function test_has_returns_true_when_the_service_exists_in_factory_array(): void
    {
        $container = new Container([stdClass::class => FactoryDummy::class]);

        self::assertTrue($container->has(stdClass::class));
    }

    public function test_has_returns_false_when_the_service_does_not_exists_in_factory_array(): void
    {
        $container = new Container([stdClass::class => FactoryDummy::class]);

        self::assertFalse($container->has(Container::class));
    }
}

final readonly class FactoryDummy implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): object
    {
        return new $serviceId();
    }
}
