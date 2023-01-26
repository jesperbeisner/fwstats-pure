<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\ResultEnum;
use Jesperbeisner\Fwstats\Exception\ActionException;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Helper\Str;
use Jesperbeisner\Fwstats\Helper\UuidV4;
use Jesperbeisner\Fwstats\Interface\ActionInterface;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Result\CreateUserActionResult;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Action\CreateUserActionTest
 */
final class CreateUserAction implements ActionInterface
{
    private string $username;
    private string $password;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function configure(array $data): CreateUserAction
    {
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new RuntimeException('No username and/or password set in "CreateUserAction::configure".');
        }

        if (!is_string($data['username']) || !is_string($data['password'])) {
            throw new RuntimeException('The username and/or password set in the "CreateUserAction::configure" method is not a string.');
        }

        if (strlen($data['username']) < 3) {
            throw new ActionException('text.username-not-long-enough');
        }

        if (strlen($data['password']) < 10) {
            throw new ActionException('text.password-not-long-enough');
        }

        if (strlen($data['password']) > 100) {
            throw new ActionException('text.password-too long');
        }

        $this->username = $data['username'];
        $this->password = $data['password'];

        return $this;
    }

    public function run(): CreateUserActionResult
    {
        if (!isset($this->username) || !isset($this->password)) {
            throw new RuntimeException('You need to call "configure" before you can call "run".');
        }

        if (null !== $this->userRepository->findOneByUsername($this->username)) {
            throw new ActionException($this->translator->translate('text.username-already-exists', ['%USERNAME%' => $this->username]));
        }

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $user = new User(null, UuidV4::create(), $this->username, $hashedPassword, Str::random(32), new DateTimeImmutable());
        $user = $this->userRepository->insert($user);

        return new CreateUserActionResult(ResultEnum::SUCCESS, $this->translator->translate('text.user-created-successfully', ['%USERNAME%' => $user->username]), ['user' => $user]);
    }
}
