<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Exception;

use Exception;

final class UnauthorizedException extends Exception
{
    public function __construct(string $text = '401 - Unauthorized')
    {
        parent::__construct($text, 401);
    }
}
