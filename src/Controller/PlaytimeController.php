<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class PlaytimeController extends AbstractController
{
    public function __construct(
        private readonly PlayerActiveSecondRepository $playerActiveSecondRepository
    ) {
    }

    public function playtime(): ResponseInterface
    {
        return new HtmlResponse('playtime/playtime.phtml', [
            'afsrvPlaytime' => $this->playerActiveSecondRepository->getPlaytimeByWorld(WorldEnum::AFSRV),
            'chaosPlaytime' => $this->playerActiveSecondRepository->getPlaytimeByWorld(WorldEnum::CHAOS)
        ]);
    }
}
