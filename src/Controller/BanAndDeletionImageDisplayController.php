<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\BanAndDeletionImageDisplayControllerTest
 */
final readonly class BanAndDeletionImageDisplayController implements ControllerInterface
{
    private const NAME_CHANGES_IMAGE = '/var/%WORLD%-bans-and-deletions.png';

    public function __construct(
        private string $rootDir,
    ) {
    }

    public function execute(Request $request): Response
    {
        $worldString = $request->getRouteParameter('world');

        if (null === $world = WorldEnum::tryFrom($worldString)) {
            return Response::html('error/error.phtml', ['message' => 'text.404-page-not-found'], 404);
        }

        $imageFileName = str_replace('%WORLD%', $world->value, $this->rootDir . BanAndDeletionImageDisplayController::NAME_CHANGES_IMAGE);

        return Response::png($imageFileName);
    }
}
