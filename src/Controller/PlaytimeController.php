<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final readonly class PlaytimeController implements ControllerInterface
{
    public function __construct(
        private PlayerActiveSecondRepository $playerActiveSecondRepository
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse('playtime/playtime.phtml', [
            'afsrvPlaytime' => $this->playerActiveSecondRepository->getPlaytimeByWorld(WorldEnum::AFSRV),
            'chaosPlaytime' => $this->playerActiveSecondRepository->getPlaytimeByWorld(WorldEnum::CHAOS)
        ]);
    }
}
