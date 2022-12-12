<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

final class Request
{
    /** @var array<string, string> */
    private array $routeParameters = [];

    /**
     * @param array<string, string> $serverParameters
     * @param array<string, string> $getParameters
     * @param array<string, string> $postParameters
     * @param array<string, string> $cookieParameters
     */
    public function __construct(
        private readonly array $serverParameters,
        private readonly array $getParameters,
        private readonly array $postParameters,
        private readonly array $cookieParameters
    ) {
    }

    public static function fromGlobals(): Request
    {
        return new Request($_SERVER, $_GET, $_POST, $_COOKIE);
    }

    public function getUri(): string
    {
        $uri = $this->serverParameters['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return rawurldecode($uri);
    }

    public function getRequestUri(): string
    {
        return $this->serverParameters['REQUEST_URI'];
    }

    public function getHttpMethod(): string
    {
        return strtoupper($this->serverParameters['REQUEST_METHOD']);
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
        return $this->getHttpMethod() === 'POST';
    }
}
