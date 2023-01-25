<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Controller\NotFoundController;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\NotFoundController
 */
final class NotFoundControllerTest extends AbstractTestCase
{
    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/test', 'REQUEST_METHOD' => 'GET'], [], [], [], []);

        $response = (new NotFoundController())->execute($request);

        self::assertSame(404, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('error/error.phtml', $response->template);
    }
}
