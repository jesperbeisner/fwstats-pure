<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\IndexController
 */
final class IndexControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();
    }

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('index/index.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }

    public function test_get_request_with_available_page(): void
    {
        $request = new Request(['REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'], ['page' => '1'], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('index/index.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }

    public function test_get_request_with_not_available_page(): void
    {
        $request = new Request(['REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'], ['page' => '999'], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(404, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('error/error.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }

    public function test_get_request_with_negative_page(): void
    {
        $request = new Request(['REQUEST_URI' => '/', 'REQUEST_METHOD' => 'GET'], ['page' => '-999'], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(404, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('error/error.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }
}
