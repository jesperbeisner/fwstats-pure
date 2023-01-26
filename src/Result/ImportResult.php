<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Result;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Result\ImportResultTest
 */
final class ImportResult
{
    /** @var array<string> */
    private array $messages = [];

    public function addMessage(string $message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @return array<string>
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
