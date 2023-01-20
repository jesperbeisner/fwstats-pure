<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class AdminController implements ControllerInterface
{
    public function execute(Request $request): Response
    {
        return Response::html('admin/admin.phtml');
    }
}
