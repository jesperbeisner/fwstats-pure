<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Tests\Unit\Action;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Exception\ActionException;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Tests\Dummy\DatabaseDummy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Jesperbeisner\Fwstats\Action\CreateUserAction
 */
class CreateUserActionTest extends TestCase
{
    private CreateUserAction $createUserAction;

    public function setUp(): void
    {
        $this->createUserAction = new CreateUserAction(new UserRepository(new DatabaseDummy()));
    }

    public function test_will_throw_ActionException_when_username_is_not_set(): void
    {
        self::expectException(ActionException::class);
        self::expectExceptionMessage('No username set in "CreateUserAction::configure".');

        $this->createUserAction->configure(['no-username' => 'test', 'password' => 'Password123']);
    }

    public function test_will_throw_ActionException_when_password_is_not_set(): void
    {
        self::expectException(ActionException::class);
        self::expectExceptionMessage('No username set in "CreateUserAction::configure".');

        $this->createUserAction->configure(['username' => 'test', 'no-password' => 'Password123']);
    }

    public function test_will_throw_ActionException_when_username_is_not_a_string(): void
    {
        self::expectException(ActionException::class);
        self::expectExceptionMessage('The username set in the "CreateUserAction::configure" method is not a string.');

        $this->createUserAction->configure(['username' => 123, 'password' => 'Password123']);
    }

    public function test_will_throw_ActionException_when_password_is_not_a_string(): void
    {
        self::expectException(ActionException::class);
        self::expectExceptionMessage('The password set in the "CreateUserAction::configure" method is not a string.');

        $this->createUserAction->configure(['username' => 'test', 'password' => 123]);
    }

    public function test_will_throw_ActionException_when_username_is_not_valid(): void
    {
        self::expectException(ActionException::class);
        self::expectExceptionMessage('The username must be at least 3 characters long.');

        $this->createUserAction->configure(['username' => 'a', 'password' => 'Password123']);
    }

    public function test_will_throw_ActionException_when_password_is_not_at_least_8_characters_long(): void
    {
        self::expectException(ActionException::class);
        self::expectExceptionMessage('The password must be at least 8 characters long.');

        $this->createUserAction->configure(['username' => 'test', 'password' => 'test']);
    }

    public function test_will_throw_ActionException_when_user_with_this_username_already_exists(): void
    {
        $database = new DatabaseDummy();
        $database->setSelectReturn([
            [
                'id' => 1,
                'uuid' => 'test',
                'username' => 'test',
                'password' => 'test',
                'token' => 'test',
                'created' => '2022-01-01',
            ],
        ]);

        $createUserAction = new CreateUserAction(new UserRepository($database));

        $createUserAction->configure(['username' => 'test', 'password' => 'Test12345']);

        self::expectException(ActionException::class);
        self::expectExceptionMessage('A user with username "test" already exists.');

        $createUserAction->run();
    }

    public function test_will_create_a_new_user_and_returns_CreateUserActionResult_with_user(): void
    {
        $this->createUserAction->configure(['username' => 'test', 'password' => 'Test12345']);

        $createUserActionResult = $this->createUserAction->run();

        self::assertTrue($createUserActionResult->isSuccess());
        self::assertArrayHasKey('user', $createUserActionResult->getData());
    }
}
