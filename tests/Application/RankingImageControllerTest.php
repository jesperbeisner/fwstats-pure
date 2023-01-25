<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Stdlib\Request;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\RankingImageController
 */
final class RankingImageControllerTest extends AbstractTestCase
{
    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/images/ranking', 'REQUEST_METHOD' => 'GET'], [], [], [], []);

        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame('image/ranking.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }
}
