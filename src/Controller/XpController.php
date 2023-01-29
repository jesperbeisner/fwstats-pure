<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class XpController implements ControllerInterface
{
    public function execute(Request $request): Response
    {
        return Response::html('xp/xp.phtml');
    }
}
