<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

final class RedirectResponse extends Response
{
    public function __construct(
        private readonly string $url,
    ) {
    }

    protected function getStatusCode(): int
    {
        return 302;
    }

    protected function getHeaders(): array
    {
        return [
            'Location: ' . $this->url,
        ];
    }

    protected function getContent(): string
    {
        return '';
    }
}
