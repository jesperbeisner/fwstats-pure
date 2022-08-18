<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

final class HomeController extends AbstractController
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly ClanRepository $clanRepository,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        return new HtmlResponse('home/home.phtml', [
            'afsrvPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::AFSRV),
            'afsrvClans' => $this->clanRepository->findAllByWorld(WorldEnum::AFSRV),
            'chaosPlayers' => $this->playerRepository->findAllByWorldAndOrderedByTotalXp(WorldEnum::CHAOS),
            'chaosClans' => $this->clanRepository->findAllByWorld(WorldEnum::CHAOS),
        ]);
    }
}
