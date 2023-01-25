<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\MethodNotAllowedControllerTest
 */
final readonly class MethodNotAllowedController implements ControllerInterface
{
    public function execute(Request $request): Response
    {
        return Response::json(['Error' => 'Method not allowed.'], 405);
    }
}
