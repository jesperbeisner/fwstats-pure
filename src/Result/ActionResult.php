<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Result;

use Jesperbeisner\Fwstats\Enum\ResultEnum;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Result\ActionResultTest
 */
class ActionResult implements ActionResultInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        protected ResultEnum $result,
        protected string $message = '',
        protected array $data = [],
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->result === ResultEnum::SUCCESS;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
