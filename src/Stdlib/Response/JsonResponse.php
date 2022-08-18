<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Response;

use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

final class JsonResponse implements ResponseInterface
{
    public function send(): never
    {
        echo 'JsonResponse!';

        exit(0);
    }
}
