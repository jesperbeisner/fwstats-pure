<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

final class DatabaseFixtureCommand extends AbstractCommand
{
    public static string $name = 'app:database-fixture';
    public static string $description = 'Loads fixtures for local development into the database.';

    public function __construct(
        private readonly string $appEnv,
    ) {
    }

    public function execute(): int
    {
        if ($this->appEnv !== 'dev') {
            $this->writeLine("Rhe 'app:database-fixture' command can only be executed in the dev environment.");

            return self::FAILURE;
        }

        $this->startTime();
        $this->writeLine("Starting the 'app:database-fixture' command...");

        $this->writeLine("Finished the 'app:database-fixture' command in {$this->getTime()} ms.");

        return self::SUCCESS;
    }
}
