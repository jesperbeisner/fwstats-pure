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

/**
 * @covers \Jesperbeisner\Fwstats\Controller\SearchController
 */
final class SearchControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();
    }

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/search', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('search/search.phtml', $response->template);
        self::assertEmpty($response->vars['players']);
    }

    public function test_get_request_with_query_and_non_existing_player(): void
    {
        $request = new Request(['REQUEST_URI' => '/search', 'REQUEST_METHOD' => 'GET'], ['query' => 'test'], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('search/search.phtml', $response->template);
        self::assertEmpty($response->vars['players']);
    }

    public function test_get_request_with_query_and_existing_player(): void
    {
        $request = new Request(['REQUEST_URI' => '/search', 'REQUEST_METHOD' => 'GET'], ['query' => 'test'], [], [], []);
        self::getContainer()->set(Request::class, $request);

        $player = new Player(null, WorldEnum::AFSRV, 1, 'test', 'Onlo', 1, 0, 1, null, null, new DateTimeImmutable());
        self::getContainer()->get(PlayerRepository::class)->insert($player);

        $response = (new Application(self::getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('search/search.phtml', $response->template);
        self::assertNotEmpty($response->vars['players']);
    }
}
