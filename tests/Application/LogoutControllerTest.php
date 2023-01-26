<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\LogoutController
 */
final class LogoutControllerTest extends AbstractTestCase
{
    use ContainerTrait;

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/logout', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
    }

    public function test_get_request_deletes_session(): void
    {
        $request = new Request(['REQUEST_URI' => '/logout', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $session = $this->getContainer()->get(SessionInterface::class);
        $session->set('test', 'test');

        self::assertSame('test', $session->get('test'));

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
        self::assertNull($session->get('test'));
    }
}
