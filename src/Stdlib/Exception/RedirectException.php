<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Exception;

use Exception;

final class RedirectException extends Exception
{
    public function __construct(
        public readonly string $route,
    ) {
        parent::__construct();
    }
}
