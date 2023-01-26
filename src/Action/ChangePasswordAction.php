<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action;

use Jesperbeisner\Fwstats\Enum\ResultEnum;
use Jesperbeisner\Fwstats\Exception\ActionException;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ActionInterface;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Result\ActionResult;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Action\ChangePasswordActionTest
 */
final class ChangePasswordAction implements ActionInterface
{
    private User $user;
    private string $password;

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function configure(array $data): ChangePasswordAction
    {
        if (!isset($data['user']) || !isset($data['password'])) {
            throw new RuntimeException('No user and/or password set in "ChangePasswordAction::configure".');
        }

        if (!$data['user'] instanceof User) {
            throw new RuntimeException(sprintf('The user set in the "CreateUserAction::configure" method is not an instance of "%s".', User::class));
        }

        if (!is_string($data['password'])) {
            throw new RuntimeException('The password set in the "CreateUserAction::configure" method is not a string.');
        }

        if (strlen($data['password']) < 10) {
            throw new ActionException('text.password-not-long-enough');
        }

        if (strlen($data['password']) > 100) {
            throw new ActionException('text.password-too-long');
        }

        $this->user = $data['user'];
        $this->password = $data['password'];

        return $this;
    }

    public function run(): ActionResultInterface
    {
        if (!isset($this->user) || !isset($this->password)) {
            throw new RuntimeException('You need to call "configure" before you can call "run".');
        }

        $this->userRepository->changePassword($this->user, password_hash($this->password, PASSWORD_DEFAULT));

        return new ActionResult(ResultEnum::SUCCESS, 'text.password-changed-successfully');
    }
}
