<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Psr\Container\ContainerInterface;

final class RequestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): Request
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return new Request(
            strtoupper($_SERVER['REQUEST_METHOD']),
            rawurldecode($uri),
            $_GET,
            $_POST,
            $_COOKIE,
        );
    }
}
