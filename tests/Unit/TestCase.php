<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit;

use Jesperbeisner\Fwstats\Stdlib\Container;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected static ?Container $container = null;

    public function setUp(): void
    {
        /** @var Container $container */
        $container = require __DIR__ . '/../../bootstrap.php';

        $container->set('appEnv', 'test');

        self::$container = $container;
    }

    public function tearDown(): void
    {
        self::$container = null;
    }
}
