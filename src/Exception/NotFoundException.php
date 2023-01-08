<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Exception;

use Exception;

final class NotFoundException extends Exception
{
    public function __construct(string $text = '404 - Page not found')
    {
        parent::__construct($text, 404);
    }
}
