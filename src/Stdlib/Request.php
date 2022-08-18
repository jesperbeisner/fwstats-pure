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
        public readonly string $httpMethod,
        public readonly string $uri,
        public readonly array $getParameters,
        public readonly array $postParameters,
        public readonly array $cookieParameters
    ) {
    }

    /**
     * @param array<string, string> $routeParameters
     */
    public function setRouteParameters(array $routeParameters): void
    {
        $this->routeParameters = $routeParameters;
    }

    /**
     * @return array<string, string>
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    public function getRouteParameter(string $id): ?string
    {
        if (array_key_exists($id, $this->routeParameters)) {
            return $this->routeParameters[$id];
        }

        return null;
    }
}
