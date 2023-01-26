<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Application;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Application;
use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;
use Jesperbeisner\Fwstats\Tests\AbstractTestCase;
use Jesperbeisner\Fwstats\Tests\ContainerTrait;

/**
 * @covers \Jesperbeisner\Fwstats\Controller\LoginController
 */
final class LoginControllerTest extends AbstractTestCase
{
    use ContainerTrait;

    public function test_get_request(): void
    {
        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'GET'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(200, $response->statusCode);
        self::assertSame(Response::CONTENT_TYPE_HTML, $response->contentType);
        self::assertSame('security/login.phtml', $response->template);
    }

    public function test_post_request_when_already_logged_in(): void
    {
        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'], [], [], [], []);
        $this->getContainer()->set(Request::class, $request);

        $user = new User(1, 'test', 'test', 'test', 'test', new DateTimeImmutable());
        $this->getContainer()->get(SessionInterface::class)->setUser($user);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/', $response->location);
    }

    public function test_post_request_without_username(): void
    {
        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'], [], ['password' => 'test'], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/login', $response->location);
        self::assertSame($this->getContainer()->get(SessionInterface::class)->getFlash(FlashEnum::ERROR), ['text.username-and-password-cant-be-empty']);
    }

    public function test_post_request_without_password(): void
    {
        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'], [], ['username' => 'test'], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/login', $response->location);
        self::assertSame($this->getContainer()->get(SessionInterface::class)->getFlash(FlashEnum::ERROR), ['text.username-and-password-cant-be-empty']);
    }

    public function test_post_request_with_non_existing_user(): void
    {
        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'], [], ['username' => 'test', 'password' => 'test'], [], []);
        $this->getContainer()->set(Request::class, $request);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/login', $response->location);
        self::assertSame($this->getContainer()->get(SessionInterface::class)->getFlash(FlashEnum::ERROR), ['text.user-with-username-does-not-exist']);
    }

    public function test_post_request_with_wrong_password(): void
    {
        $this->loadMigrations();

        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'], [], ['username' => 'test', 'password' => 'test'], [], []);
        $this->getContainer()->set(Request::class, $request);

        $user = new User(1, 'test', 'test', '123', 'test', new DateTimeImmutable());
        $this->getContainer()->get(UserRepository::class)->insert($user);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/login', $response->location);
        self::assertSame($this->getContainer()->get(SessionInterface::class)->getFlash(FlashEnum::ERROR), ['text.password-does-not-match']);
    }

    public function test_post_request(): void
    {
        $this->loadMigrations();

        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'], [], ['username' => 'test', 'password' => 'test'], [], []);
        $this->getContainer()->set(Request::class, $request);

        $user = new User(1, 'test', 'test', password_hash('test', PASSWORD_DEFAULT), 'test', new DateTimeImmutable());
        $this->getContainer()->get(UserRepository::class)->insert($user);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/admin', $response->location);
        self::assertSame($this->getContainer()->get(SessionInterface::class)->getFlash(FlashEnum::SUCCESS), ['text.login-success']);
    }

    public function test_post_request_with_redirect(): void
    {
        $this->loadMigrations();

        $request = new Request(['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'], [], ['username' => 'test', 'password' => 'test'], [], []);
        $this->getContainer()->set(Request::class, $request);

        $this->getContainer()->get(SessionInterface::class)->set('security_request_uri', '/test');

        $user = new User(1, 'test', 'test', password_hash('test', PASSWORD_DEFAULT), 'test', new DateTimeImmutable());
        $this->getContainer()->get(UserRepository::class)->insert($user);

        $response = (new Application($this->getContainer()))->handle($request);

        self::assertSame(302, $response->statusCode);
        self::assertSame('/test', $response->location);
        self::assertSame($this->getContainer()->get(SessionInterface::class)->getFlash(FlashEnum::SUCCESS), ['text.login-success']);
    }
}
