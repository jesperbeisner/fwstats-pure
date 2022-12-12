<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Action\Exception\ActionException;
use Jesperbeisner\Fwstats\Action\Interface\ActionInterface;
use Jesperbeisner\Fwstats\Action\Interface\ActionResultInterface;
use Jesperbeisner\Fwstats\Action\Result\CreateUserActionResult;
use Jesperbeisner\Fwstats\Helper\UuidV4;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;

final class CreateUserAction implements ActionInterface
{
    private string $email;
    private string $password;

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function configure(array $data): void
    {
        if (!isset($data['email'])) {
            throw new ActionException("No email set in the 'AbstractAction::configure' method.");
        }

        if (!isset($data['password'])) {
            throw new ActionException("No password set in the 'AbstractAction::configure' method.");
        }

        if (!is_string($data['email'])) {
            throw new ActionException("The email set in the 'AbstractAction::configure' method is not a string.");
        }

        if (!is_string($data['password'])) {
            throw new ActionException("The password set in the 'AbstractAction::configure' method is not a string.");
        }

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            throw new ActionException("The email '{$data['email']}' is not valid email address.");
        }

        if (strlen($data['password']) < 8) {
            throw new ActionException("The password must be at least 8 characters long.");
        }

        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public function run(): CreateUserActionResult
    {
        if (!isset($this->email) || !isset($this->password)) {
            throw new ActionException("You need to run 'configure' before you can use 'run'.");
        }

        if (null !== $this->userRepository->findOneByEmail($this->email)) {
            throw new ActionException("A user with email '$this->email' already exists.");
        }

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $user = new User(UuidV4::create(), $this->email, $hashedPassword, new DateTimeImmutable());

        $this->userRepository->insert($user);

        return new CreateUserActionResult(ActionResultInterface::SUCCESS, ['user' => $user]);
    }
}
