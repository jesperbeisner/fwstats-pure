<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Exception\RuntimeException;

final readonly class SetupService
{
    public function __construct(
        private string $setupFileName,
        private MigrationService $migrationService,
        private CreateUserAction $createUserAction,
    ) {
    }

    public function setup(): void
    {
        if (is_file($this->setupFileName)) {
            return;
        }

        $this->migrationService->loadMigrations();

        $this->createUserAction->configure(['username' => 'admin', 'password' => 'Password12345']);
        $this->createUserAction->run();

        if (false === touch($this->setupFileName)) {
            throw new RuntimeException(sprintf('Could not create setup file "%s".', $this->setupFileName));
        }
    }
}
