<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\UnauthorizedControllerTest
 */
final readonly class UnauthorizedController implements ControllerInterface
{
    public function execute(Request $request): Response
    {
        return Response::json(['Error' => 'No token was specified or the token is not valid.'], 401);
    }
}
