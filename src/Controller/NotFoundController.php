<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\NotFoundControllerTest
 */
final readonly class NotFoundController implements ControllerInterface
{
    public function execute(Request $request): Response
    {
        return Response::html('error/error.phtml', ['message' => 'text.404-page-not-found'], 404);
    }
}
