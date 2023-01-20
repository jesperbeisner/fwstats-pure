<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Exception\ActionException;
use Jesperbeisner\Fwstats\Helper\Str;
use Jesperbeisner\Fwstats\Helper\UuidV4;
use Jesperbeisner\Fwstats\Interface\ActionInterface;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Result\CreateUserActionResult;

final class CreateUserAction implements ActionInterface
{
    private string $username;
    private string $password;

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function configure(array $data): void
    {
        if (!isset($data['username'])) {
            throw new ActionException('No username set in "CreateUserAction::configure".');
        }

        if (!isset($data['password'])) {
            throw new ActionException('No username set in "CreateUserAction::configure".');
        }

        if (!is_string($data['username'])) {
            throw new ActionException('The username set in the "CreateUserAction::configure" method is not a string.');
        }

        if (!is_string($data['password'])) {
            throw new ActionException('The password set in the "CreateUserAction::configure" method is not a string.');
        }

        if (strlen($data['username']) < 3) {
            throw new ActionException('The username must be at least 3 characters long.');
        }

        if (strlen($data['password']) < 8) {
            throw new ActionException('The password must be at least 8 characters long.');
        }

        $this->username = $data['username'];
        $this->password = $data['password'];
    }

    public function run(): CreateUserActionResult
    {
        if (!isset($this->username) || !isset($this->password)) {
            throw new ActionException("You need to run 'configure' before you can use 'run'.");
        }

        if (null !== $this->userRepository->findOneByUsername($this->username)) {
            throw new ActionException(sprintf('A user with username "%s" already exists.', $this->username));
        }

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $user = new User(null, UuidV4::create(), $this->username, $hashedPassword, Str::random(32), new DateTimeImmutable());

        $user = $this->userRepository->insert($user);

        return new CreateUserActionResult(ActionResultInterface::SUCCESS, ['user' => $user]);
    }
}
