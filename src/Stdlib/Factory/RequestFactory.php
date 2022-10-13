<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final class RequestFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): Request
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        return new Request(
            rawurldecode($uri),
            $_SERVER['REQUEST_URI'],
            strtoupper($_SERVER['REQUEST_METHOD']),
            $_GET,
            $_POST,
            $_COOKIE,
        );
    }
}
