<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

abstract class Response implements ResponseInterface
{
    abstract protected function getStatusCode(): int;

    /**
     * @return array<string>
     */
    abstract protected function getHeaders(): array;
    abstract protected function getContent(): string;

    public function send(): never
    {
        http_response_code($this->getStatusCode());

        foreach ($this->getHeaders() as $header) {
            header($header);
        }

        echo $this->getContent();

        exit(0);
    }
}
