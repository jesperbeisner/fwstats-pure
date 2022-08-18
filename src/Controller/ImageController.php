<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Response\ImageResponse;

final class ImageController extends AbstractController
{
    private const RANKING_IMAGE = ROOT_DIR . '/data/images/[WORLD]-ranking.png';

    public function __construct(
        private readonly Request $request,
    ) {
    }

    public function ranking(): ResponseInterface
    {
        return new HtmlResponse('image/ranking-image.phtml');
    }

    public function image(): ResponseInterface
    {
        /** @var string $world */
        $world = $this->request->getRouteParameter('world');

        if (!in_array($world, [WorldEnum::AFSRV->value, WorldEnum::CHAOS->value], true)) {
            return new HtmlResponse('errors/404.phtml', statusCode: 404);
        }

        return new ImageResponse(str_replace('[WORLD]', $world, self::RANKING_IMAGE));
    }
}
