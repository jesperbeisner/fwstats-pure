<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Interface;

interface ResponseInterface
{
    public function send(): never;
}
