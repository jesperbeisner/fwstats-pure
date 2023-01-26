<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests;

trait ContainerTrait
{
    public function setUp(): void
    {
        $this->setUpContainer();
    }

    public function tearDown(): void
    {
        $this->tearDownContainer();
    }
}
