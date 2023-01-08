<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\JsonResponse;

final class ControllerWithoutReadonly implements ControllerInterface
{
    public function __invoke(): ResponseInterface
    {
        return new JsonResponse();
    }
}
