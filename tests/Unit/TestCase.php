<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit;

use Jesperbeisner\Fwstats\Stdlib\ServiceContainer;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected ServiceContainer $serviceContainer;

    public function setUp(): void
    {
        $this->serviceContainer = require __DIR__ . '/../../bootstrap.php';
    }
}
