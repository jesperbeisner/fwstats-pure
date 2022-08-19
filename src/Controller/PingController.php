<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\JsonResponse;

final class PingController extends AbstractController
{
    public function ping(): ResponseInterface
    {
        return new JsonResponse(['ping' => 'pong']);
    }
}
