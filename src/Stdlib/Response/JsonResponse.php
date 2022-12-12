<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;
use JsonException;

final class JsonResponse extends Response
{
    /**
     * @param array<string, mixed> $vars
     */
    public function __construct(
        private readonly array $vars = [],
        private readonly int $statusCode = 200,
    ) {
    }

    protected function getStatusCode(): int
    {
        return $this->statusCode;
    }

    protected function getHeaders(): array
    {
        return ['Content-Type: application/json'];
    }

    protected function getContent(): string
    {
        try {
            $json = json_encode($this->vars, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException('Could not encode the json response.');
        }

        return $json;
    }
}
