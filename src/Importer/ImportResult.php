<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Importer;

final class ImportResult
{
    /** @var string[] */
    private array $messages = [];

    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
