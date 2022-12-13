<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Response\ImageResponse;

final class ImageRenderController implements ControllerInterface
{
    private const RANKING_IMAGE = '/data/images/[WORLD]-ranking.png';

    public function __construct(
        private readonly Config $config,
        private readonly Request $request,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        /** @var string $worldString */
        $worldString = $this->request->getRouteParameter('world');

        if (null === $world = WorldEnum::tryFrom($worldString)) {
            return new HtmlResponse('error.phtml', ['404 - Page not found'], 404);
        }

        $imageFileName = str_replace('[WORLD]', $world->value, $this->config->getRootDir() . ImageRenderController::RANKING_IMAGE);

        return new ImageResponse($imageFileName);
    }
}
