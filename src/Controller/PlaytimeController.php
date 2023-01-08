<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class PlaytimeController implements ControllerInterface
{
    public function __construct(
        private PlayerActiveSecondRepository $playerActiveSecondRepository
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('playtime/playtime.phtml', [
            'afsrvPlaytime' => $this->playerActiveSecondRepository->getPlaytimeByWorld(WorldEnum::AFSRV),
            'chaosPlaytime' => $this->playerActiveSecondRepository->getPlaytimeByWorld(WorldEnum::CHAOS)
        ]);
    }
}
