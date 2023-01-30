<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\NameChangeImageController
 */
final class NameChangeImageControllerTest extends AbstractTestCase
{
    use ContainerTrait;

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/images/changes/names', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame('image/name-changes.phtml', $response->template);
        self::assertNotEmpty($response->vars);
    }
}
