<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Stdlib\Request;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\LocaleController
 */
final class LocaleControllerTest extends AbstractTestCase
{
    public function test_get_request_without_locale(): void
    {
        $request = new Request(['REQUEST_URI' => '/locale', 'REQUEST_METHOD' => 'GET'], [], [], [], []);

        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
        self::assertEmpty($response->getCookies());
    }

    public function test_get_request_with_wrong_locale(): void
    {
        $request = new Request(['REQUEST_URI' => '/locale', 'REQUEST_METHOD' => 'GET'], ['locale' => 'test'], [], [], []);

        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
        self::assertEmpty($response->getCookies());
    }

    public function test_get_request_with_locale(): void
    {
        $request = new Request(['REQUEST_URI' => '/locale', 'REQUEST_METHOD' => 'GET'], ['locale' => 'de'], [], [], []);

        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
        self::assertSame(['locale' => 'de'], $response->getCookies());
    }
}
