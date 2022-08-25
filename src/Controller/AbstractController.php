<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;

abstract class AbstractController implements ControllerInterface
{
    protected function notFoundException(string $text = '404 - Page not found'): void
    {
        throw new NotFoundException($text);
    }
}
