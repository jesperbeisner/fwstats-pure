<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit;

use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected static ?ServiceContainer $serviceContainer = null;

    public function setUp(): void
    {
        if (self::$serviceContainer === null) {
            self::$serviceContainer = require __DIR__ . '/../../bootstrap.php';
        }
    }
}
