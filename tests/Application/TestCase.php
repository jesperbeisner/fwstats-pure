<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    protected ?ContainerInterface $container = null;

    public function setUp(): void
    {
        /** @var ContainerInterface $container */
        $container = require __DIR__ . '/../../bootstrap.php';

        /** @var Config $config */
        $config = $container->get(Config::class);
        $config->set('app_env', 'test');

        $this->container = $container;
    }

    public function tearDown(): void
    {
        $this->container = null;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->container ?? throw new RuntimeException('This should never happen?');
    }
}
