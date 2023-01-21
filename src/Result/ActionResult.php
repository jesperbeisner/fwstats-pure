<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Result;

use Jesperbeisner\Fwstats\Exception\ActionResultException;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;

class ActionResult implements ActionResultInterface
{
    protected readonly int $result;

    /** @var mixed[] */
    protected readonly array $data;
    protected readonly string $message;

    /**
     * @param mixed[] $data
     */
    public function __construct(int $result, array $data = [], string $message = '')
    {
        if (!in_array($result, [self::SUCCESS, self::FAILURE], true)) {
            throw new ActionResultException('Only 0 and 1 are valid values for $result.');
        }

        $this->result = $result;
        $this->data = $data;
        $this->message = $message;
    }

    public function isSuccess(): bool
    {
        return $this->result === self::SUCCESS;
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
