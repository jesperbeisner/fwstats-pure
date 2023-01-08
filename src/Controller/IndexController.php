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
        return Response::html('index/index.phtml', [
            'afsrvPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::AFSRV),
            'chaosPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::CHAOS),
            'afsrvClans' => $this->clanRepository->findAllByWorld(WorldEnum::AFSRV),
            'chaosClans' => $this->clanRepository->findAllByWorld(WorldEnum::CHAOS),
            'login' => $request->getGetParameter('login'),
            'logout' => $request->getGetParameter('logout'),
        ]);
    }
}
