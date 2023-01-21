<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Exception\ActionException;

final class CreateUserCommand extends AbstractCommand
{
    public static string $name = 'app:create-user';
    public static string $description = 'Creates a new user.';

    public function __construct(
        private readonly CreateUserAction $createUserAction,
    ) {
    }

    public function execute(): int
    {
        $this->startTime();
        $this->writeLine("Starting the 'app:create-user' command...");

        if (!isset($this->arguments[2]) || !isset($this->arguments[3])) {
            $this->writeLine("Error: You forgot to pass the username and/or password to the 'app:create-user' command.");

            return self::FAILURE;
        }

        try {
            $result = $this->createUserAction->configure(['username' => $this->arguments[2], 'password' => $this->arguments[3]])->run();
        } catch (ActionException $e) {
            $this->writeLine($e->getMessage());

            return self::FAILURE;
        }

        $this->writeLine($result->getMessage());
        $this->writeLine("Finished the 'app:create-user' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
