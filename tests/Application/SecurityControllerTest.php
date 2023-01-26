<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Controller\SecurityController;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\SecurityController
 */
final class SecurityControllerTest extends AbstractTestCase
{
    use ContainerTrait;

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/test', 'REQUEST_METHOD' => 'GET'], [], [], [], []);

        $response = (new SecurityController())->execute($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/login', $response->location);
    }
}
