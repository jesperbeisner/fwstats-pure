<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class SearchController implements ControllerInterface
{
    public function __construct(
        private PlayerRepository $playerRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        $query = $request->getGetParameter('query');

        if ($query === null || $query === '') {
            return Response::html('search/search.phtml', ['players' => []]);
        }

        return Response::html('search/search.phtml', ['players' => $this->playerRepository->searchPlayers($query)]);
    }
}
