<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\ResetActionFreewarController
 */
final class ResetActionFreewarControllerTest extends AbstractTestCase
{
    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/admin/reset-action-freewar', 'REQUEST_METHOD' => 'GET'], [], [], [], []);

        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(405, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_JSON, $response->contentType);
        self::assertSame('{"Error":"Method not allowed."}', $response->content);
    }

    public function test_post_request_without_login(): void
    {
        $request = new Request(['REQUEST_URI' => '/admin/reset-action-freewar', 'REQUEST_METHOD' => 'POST'], [], [], [], []);

        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/login', $response->location);
    }

    public function test_post_request_with_login(): void
    {
        $request = new Request(['REQUEST_URI' => '/admin/reset-action-freewar', 'REQUEST_METHOD' => 'POST'], [], [], [], []);

        $this->getContainer()->set(Request::class, $request);

        $session = $this->getContainer()->get(SessionInterface::class);
        $session->setUser(new User(1, 'test', 'test', 'test', 'test', new DateTimeImmutable()));

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(303, $response->statusCode);
        self::assertSame('/admin', $response->location);
    }
}
