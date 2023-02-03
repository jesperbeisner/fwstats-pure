<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Service\MigrationService;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Tests\Doubles\SessionFake;
use Jesperbeisner\Fwstats\Tests\Doubles\TranslatorDummy;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use RuntimeException;

abstract class AbstractTestCase extends PHPUnitTestCase
{
    protected static ?ContainerInterface $container = null;

    protected static function setUpContainer(): void
    {
        /** @var ContainerInterface $container */
        $container = require __DIR__ . '/../bootstrap.php';

        $config = $container->get(Config::class);

        $config->set('database_file', __DIR__ . '/../var/sqlite-test.db');
        $config->set('setup_file', __DIR__ . '/../var/setup-test.txt');

        $container->set(SessionInterface::class, new SessionFake());
        $container->set(TranslatorInterface::class, new TranslatorDummy());

        static::$container = $container;
    }

    protected static function setUpDatabase(): void
    {
        self::deleteDatabaseFiles();

        static::getContainer()->get(MigrationService::class)->loadMigrations();
    }

    protected static function getContainer(): ContainerInterface
    {
        return static::$container ?? throw new RuntimeException('Did you forget to call "setUpContainer" beforehand?');
    }

    protected function tearDown(): void
    {
        self::deleteDatabaseFiles();
    }

    private static function deleteDatabaseFiles(): void
    {
        $dbTestFile = __DIR__ . '/../var/sqlite-test.db';
        if (file_exists($dbTestFile) && false === unlink($dbTestFile)) {
            throw new RuntimeException(sprintf('Could not unlink file "%s"', $dbTestFile));
        }

        $setupTestFile = __DIR__ . '/../var/setup-test.txt';
        if (file_exists($setupTestFile) && false === unlink($setupTestFile)) {
            throw new RuntimeException(sprintf('Could not unlink file "%s"', $setupTestFile));
        }
    }
}
