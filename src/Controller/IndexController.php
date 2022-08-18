<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class IndexController extends AbstractController
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly ClanRepository $clanRepository,
    ) {
    }

    public function index(): ResponseInterface
    {
        return new HtmlResponse('index/index.phtml', [
            'afsrvPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::AFSRV),
            'chaosPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::CHAOS),
            'afsrvClans' => $this->clanRepository->findAllByWorld(WorldEnum::AFSRV),
            'chaosClans' => $this->clanRepository->findAllByWorld(WorldEnum::CHAOS),
        ]);
    }
}
