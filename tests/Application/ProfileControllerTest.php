<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\ProfileController
 */
final class ProfileControllerTest extends AbstractTestCase
{
    use ContainerTrait;

    public function test_get_request_with_non_existing_world(): void
    {
        $request = new Request(['REQUEST_URI' => '/profile/test/1', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(404, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('error/error.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }

    public function test_get_request_with_non_numeric_player_id(): void
    {
        $request = new Request(['REQUEST_URI' => '/profile/afsrv/test', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(404, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('error/error.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }

    public function test_get_request_with_non_existing_player(): void
    {
        $request = new Request(['REQUEST_URI' => '/profile/afsrv/1', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(404, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('error/error.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }

    public function test_get_request_with_existing_player(): void
    {
        $this->loadMigrations();

        $request = new Request(['REQUEST_URI' => '/profile/afsrv/1', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $player = new Player(null, WorldEnum::AFSRV, 1, 'test', 'Onlo', 1, 0, 1, null, null, new DateTimeImmutable());
        $this->getContainer()->get(PlayerRepository::class)->insert($player);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('profile/profile.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }
}
