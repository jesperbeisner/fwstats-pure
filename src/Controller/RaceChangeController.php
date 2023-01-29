<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\RaceChangeControllerTest
 */
final readonly class RaceChangeController implements ControllerInterface
{
    public function __construct(
        private PlayerRaceHistoryRepository $playerRaceHistoryRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('changes/races.phtml', [
            'afsrvRaceChanges' => $this->playerRaceHistoryRepository->getRaceChangesByWorld(WorldEnum::AFSRV),
            'chaosRaceChanges' => $this->playerRaceHistoryRepository->getRaceChangesByWorld(WorldEnum::CHAOS),
        ]);
    }
}
