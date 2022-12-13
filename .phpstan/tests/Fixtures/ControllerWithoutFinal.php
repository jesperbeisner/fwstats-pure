<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\JsonResponse;

readonly class ControllerWithoutFinal implements ControllerInterface
{
    public function __invoke(): ResponseInterface
    {
        return new JsonResponse();
    }
}
