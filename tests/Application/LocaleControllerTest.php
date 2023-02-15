<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Controller\LocaleController;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(LocaleController::class)]
final class LocaleControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();
    }

    public function test_get_request_without_locale(): void
    {
        $request = new Request(['REQUEST_URI' => '/locale', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
        self::assertEmpty($response->getCookies());
    }

    public function test_get_request_with_wrong_locale(): void
    {
        $request = new Request(['REQUEST_URI' => '/locale', 'REQUEST_METHOD' => 'GET'], ['locale' => 'test'], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
        self::assertEmpty($response->getCookies());
    }

    public function test_get_request_with_locale(): void
    {
        $request = new Request(['REQUEST_URI' => '/locale', 'REQUEST_METHOD' => 'GET'], ['locale' => 'de'], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
        self::assertSame(['locale' => 'de'], $response->getCookies());
    }
}
