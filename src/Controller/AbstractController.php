<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Exception\UnauthorizedException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;

abstract class AbstractController implements ControllerInterface
{
    protected function notFoundException(string $text = '404 - Page not found'): void
    {
        throw new NotFoundException($text, 404);
    }

    protected function unauthorizedException(string $text = '401 - Unauthorized'): void
    {
        throw new UnauthorizedException($text, 401);
    }
}
