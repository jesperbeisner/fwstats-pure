<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;

final class HomeController extends AbstractController
{
    public function __construct(
        private readonly PlayerRepository $playerRepository
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $players = $this->playerRepository->findAllOrderedByTotalXp();

        return new HtmlResponse('home/home.phtml', [
            'players' => $players,
        ]);
    }
}
