<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

final class Request
{
    /**
     * @param mixed[] $getParameters
     * @param mixed[] $postParameters
     * @param mixed[] $cookieParameters
     */
    public function __construct(
        public readonly string $httpMethod,
        public readonly string $uri,
        public readonly array $getParameters,
        public readonly array $postParameters,
        public readonly array $cookieParameters
    ) {
    }
}
