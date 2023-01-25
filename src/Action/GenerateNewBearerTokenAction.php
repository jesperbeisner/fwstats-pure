<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ActionInterface;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;
use Jesperbeisner\Fwstats\Model\User;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Result\ActionResult;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Action\GenerateNewBearerTokenActionTest
 */
final class GenerateNewBearerTokenAction implements ActionInterface
{
    private User $user;
    private string $token;

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function configure(array $data): GenerateNewBearerTokenAction
    {
        if (!isset($data['user']) || !isset($data['token'])) {
            throw new RuntimeException('No user and/or token set in "GenerateNewBearerTokenAction::configure".');
        }

        if (!$data['user'] instanceof User) {
            throw new RuntimeException(sprintf('The user set in the "GenerateNewBearerTokenAction::configure" method is not an instance of "%s".', User::class));
        }

        if (!is_string($data['token'])) {
            throw new RuntimeException('The token set in the "GenerateNewBearerTokenAction::configure" method is not a string.');
        }

        if (strlen($data['token']) !== 32) {
            throw new RuntimeException('The token set in the "GenerateNewBearerTokenAction::configure" method is not exactly 32 characters long.');
        }

        $this->user = $data['user'];
        $this->token = $data['token'];

        return $this;
    }

    public function run(): ActionResultInterface
    {
        if (!isset($this->user) || !isset($this->token)) {
            throw new RuntimeException('You need to call "configure" before you can call "run".');
        }

        $this->userRepository->changeToken($this->user, $this->token);

        return new ActionResult(ActionResultInterface::SUCCESS, 'text.new-token-generated-successfully');
    }
}
