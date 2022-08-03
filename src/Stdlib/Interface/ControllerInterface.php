<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

interface ControllerInterface
{
    public function __invoke(): ResponseInterface;
}
