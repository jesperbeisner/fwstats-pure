<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Interface\CronjobInterface;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\Doubles\CronjobDummy;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\CronjobController
 */
final class CronjobControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();
    }

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/cronjob', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        self::getContainer()->set(Request::class, $request);

        self::getContainer()->set(CronjobInterface::class, new CronjobDummy());

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(405, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_JSON, $response->contentType);
        self::assertSame('{"Error":"Method not allowed."}', $response->content);
    }

    public function test_post_request_without_token(): void
    {
        $request = new Request(['REQUEST_URI' => '/cronjob', 'REQUEST_METHOD' => 'POST'], [], [], [], []);
        self::getContainer()->set(Request::class, $request);

        self::getContainer()->set(CronjobInterface::class, new CronjobDummy());

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(401, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_JSON, $response->contentType);
        self::assertSame('{"Error":"No token was specified or the token is not valid."}', $response->content);
    }

    public function test_post_request_with_non_valid_token(): void
    {
        $request = new Request(['REQUEST_URI' => '/cronjob', 'REQUEST_METHOD' => 'POST'], [], [], [], ['Authorization' => 'Bearer test']);
        self::getContainer()->set(Request::class, $request);

        self::getContainer()->set(CronjobInterface::class, new CronjobDummy());

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(401, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_JSON, $response->contentType);
        self::assertSame('{"Error":"No token was specified or the token is not valid."}', $response->content);
    }

    public function test_post_request_with_valid_token_but_cronjob_not_allowed_to_run(): void
    {
        $request = new Request(['REQUEST_URI' => '/cronjob', 'REQUEST_METHOD' => 'POST'], [], [], [], ['Authorization' => 'Bearer test']);
        self::getContainer()->set(Request::class, $request);

        self::getContainer()->set(CronjobInterface::class, new CronjobDummy(false));

        $user = new User(1, 'test', 'test', 'test', 'test', new DateTimeImmutable());
        self::getContainer()->get(UserRepository::class)->insert($user);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_JSON, $response->contentType);
        self::assertSame('{"Success":"Request received but cronjob is currently not allowed to run."}', $response->content);
    }

    public function test_post_request_with_valid_token(): void
    {
        $request = new Request(['REQUEST_URI' => '/cronjob', 'REQUEST_METHOD' => 'POST'], [], [], [], ['Authorization' => 'Bearer test']);
        self::getContainer()->set(Request::class, $request);

        self::getContainer()->set(CronjobInterface::class, new CronjobDummy());

        $user = new User(1, 'test', 'test', 'test', 'test', new DateTimeImmutable());
        self::getContainer()->get(UserRepository::class)->insert($user);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_JSON, $response->contentType);
        self::assertSame('{"Success":"Cronjob successfully executed."}', $response->content);
    }
}
