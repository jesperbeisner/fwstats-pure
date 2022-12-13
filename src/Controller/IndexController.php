<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final readonly class IndexController implements ControllerInterface
{
    public function __construct(
        private Request $request,
        private PlayerRepository $playerRepository,
        private ClanRepository $clanRepository,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse('index/index.phtml', [
            'afsrvPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::AFSRV),
            'chaosPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::CHAOS),
            'afsrvClans' => $this->clanRepository->findAllByWorld(WorldEnum::AFSRV),
            'chaosClans' => $this->clanRepository->findAllByWorld(WorldEnum::CHAOS),
            'login' => $this->request->getGetParameter('login'),
            'logout' => $this->request->getGetParameter('logout'),
        ]);
    }
}
