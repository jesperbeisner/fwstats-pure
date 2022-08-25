<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Exception;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

final class ServiceNotFoundException extends Exception implements NotFoundExceptionInterface
{
}
