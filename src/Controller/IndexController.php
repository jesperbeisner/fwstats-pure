<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class IndexController implements ControllerInterface
{
    public function __construct(
        private PlayerRepository $playerRepository,
        private ClanRepository $clanRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        $page = $request->getGetParameter('page');
        $page = is_numeric($page) ? (int) $page : 1;
        $offset = ($page * 100) - 100;

        $maxAmountOfPlayersInSingleWorld = $this->playerRepository->getMaxAmountOfPlayersInSingleWorld();
        if ($offset > $maxAmountOfPlayersInSingleWorld) {
            return Response::html('error/error.phtml', ['message' => '404 - Page not found'], 404);
        }

        $afsrvPlayers = $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::AFSRV, $offset);
        $chaosPlayers = $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::CHAOS, $offset);

        $afsrvClans = $this->clanRepository->findAllByWorld(WorldEnum::AFSRV);
        $chaosClans = $this->clanRepository->findAllByWorld(WorldEnum::CHAOS);

        return Response::html('index/index.phtml', [
            'afsrvPlayers' => $afsrvPlayers,
            'chaosPlayers' => $chaosPlayers,
            'afsrvClans' => $afsrvClans,
            'chaosClans' => $chaosClans,
            'page' => $page,
            'hasPreviousSite' => $page > 1,
            'hasNextSite' => $offset + 100 < $maxAmountOfPlayersInSingleWorld,
            'availablePages' => (int) ceil($maxAmountOfPlayersInSingleWorld / 100)
        ]);
    }
}
