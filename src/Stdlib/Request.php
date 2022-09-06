<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

final class Request
{
    /** @var array<string, string> */
    private array $routeParameters = [];

    /**
     * @param mixed[] $getParameters
     * @param mixed[] $postParameters
     * @param mixed[] $cookieParameters
     */
    public function __construct(
        public readonly string $uri,
        public readonly string $fullUri,
        public readonly string $httpMethod,
        private readonly array $getParameters,
        private readonly array $postParameters,
        private readonly array $cookieParameters
    ) {
    }

    /**
     * @param array<string, string> $routeParameters
     */
    public function setRouteParameters(array $routeParameters): void
    {
        $this->routeParameters = $routeParameters;
    }

    public function getRouteParameter(string $id): ?string
    {
        if (array_key_exists($id, $this->routeParameters)) {
            return $this->routeParameters[$id];
        }

        return null;
    }

    public function getGetParameter(string $id): ?string
    {
        if (array_key_exists($id, $this->getParameters)) {
            return $this->getParameters[$id];
        }

        return null;
    }

    public function getPostParameter(string $id): ?string
    {
        if (array_key_exists($id, $this->postParameters)) {
            return $this->postParameters[$id];
        }

        return null;
    }

    public function getCookieParameter(string $id): ?string
    {
        if (array_key_exists($id, $this->cookieParameters)) {
            return $this->cookieParameters[$id];
        }

        return null;
    }

    public function isPost(): bool
    {
        return strtoupper($this->httpMethod) === 'POST';
    }
}
