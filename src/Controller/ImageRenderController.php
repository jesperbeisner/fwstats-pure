<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class ImageRenderController implements ControllerInterface
{
    private const RANKING_IMAGE = '/var/[WORLD]-ranking.png';

    public function __construct(
        private string $rootDir,
    ) {
    }

    public function execute(Request $request): Response
    {
        /** @var string $worldString */
        $worldString = $request->getRouteParameter('world');

        if (null === $world = WorldEnum::tryFrom($worldString)) {
            return Response::html('error/error.phtml', ['message' => '404 - Page not found'], 404);
        }

        $imageFileName = str_replace('[WORLD]', $world->value, $this->rootDir . ImageRenderController::RANKING_IMAGE);

        return Response::png($imageFileName);
    }
}
