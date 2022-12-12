<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Middleware;

use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

interface MiddlewareInterface
{
    public function run(): ?ResponseInterface;
}
