<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use Jesperbeisner\Fwstats\Controller\SecurityController;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(SecurityController::class)]
final class SecurityControllerTest extends AbstractTestCase
{
    protected function setUp(): void
    {
        self::setUpContainer();
        self::setUpDatabase();
    }

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/test', 'REQUEST_METHOD' => 'GET'], [], [], [], []);

        $response = (new SecurityController())->execute($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/login', $response->location);
    }
}
