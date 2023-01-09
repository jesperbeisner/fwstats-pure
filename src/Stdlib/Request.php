<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib;

use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;

final class Request
{
    /** @var null|class-string<ControllerInterface> */
    private ?string $controller = null;

    /** @var array<string, string> */
    private array $routeParameters = [];

    /**
     * @param array<string, string> $serverParameters
     * @param array<string, string> $getParameters
     * @param array<string, string> $postParameters
     * @param array<string, string> $cookieParameters
     * @param array<string, string> $headers
     */
    public function __construct(
        private readonly array $serverParameters,
        private readonly array $getParameters,
        private readonly array $postParameters,
        private readonly array $cookieParameters,
        private readonly array $headers,
    ) {
    }

    public static function fromGlobals(): Request
    {
        return new Request($_SERVER, $_GET, $_POST, $_COOKIE, getallheaders());
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
     * @param class-string<ControllerInterface> $controller
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return null|class-string<ControllerInterface>
     */
    public function getController(): ?string
    {
        return $this->controller;
    }

    /**
     * @param array<string, string> $routeParameters
     */
    public function setRouteParameters(array $routeParameters): void
    {
        $this->routeParameters = $routeParameters;
    }

    public function getRouteParameter(string $id): string
    {
        return $this->routeParameters[$id] ?? throw new RuntimeException(sprintf('Route parameter with id "%s" does not exist.', $id));
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

    public function getBearerToken(): ?string
    {
        if (!array_key_exists('Authorization', $this->headers)) {
            return null;
        }

        $authorizationHeader = $this->headers['Authorization'];
        $authorizationHeaderParts = explode(" ", $authorizationHeader);

        if (!array_key_exists(1, $authorizationHeaderParts)) {
            return null;
        }

        return trim($authorizationHeaderParts[1]);
    }

    public function isGet(): bool
    {
        return $this->getHttpMethod() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->getHttpMethod() === 'POST';
    }

    public function isPut(): bool
    {
        return $this->getHttpMethod() === 'PUT';
    }

    public function isPatch(): bool
    {
        return $this->getHttpMethod() === 'PATCH';
    }

    public function isDelete(): bool
    {
        return $this->getHttpMethod() === 'DELETE';
    }
}
