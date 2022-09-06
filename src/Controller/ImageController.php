<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Response\ImageResponse;

final class ImageController extends AbstractController
{
    private const RANKING_IMAGE = '/data/images/[WORLD]-ranking.png';

    public function __construct(
        private readonly string $rootDir,
        private readonly Request $request,
    ) {
    }

    public function ranking(): ResponseInterface
    {
        return new HtmlResponse('image/ranking.phtml');
    }

    public function image(): ResponseInterface
    {
        /** @var string $worldString */
        $worldString = $this->request->getRouteParameter('world');
        if (null === $world = WorldEnum::tryFrom($worldString)) {
            throw new NotFoundException();
        }

        return new ImageResponse(str_replace('[WORLD]', $world->value, $this->rootDir . self::RANKING_IMAGE));
    }
}
