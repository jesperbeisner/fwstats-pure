<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\PlaytimeService;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class ProfileController extends AbstractController
{
    public function __construct(
        private readonly Request $request,
        private readonly PlayerRepository $playerRepository,
        private readonly PlaytimeService $playtimeService,
    ) {
    }

    public function profile(): ResponseInterface
    {
        /** @var string $world */
        $world = $this->request->getRouteParameter('world');
        if (null === $world = WorldEnum::tryFrom($world)) {
            $this->notFoundException();
        }

        /** @var string $playerId */
        $playerId = $this->request->getRouteParameter('id');
        if (!is_numeric($playerId)) {
            $this->notFoundException();
        }

        if (null === $player = $this->playerRepository->find($world, (int) $playerId)) {
            $this->notFoundException();
        }

        return new HtmlResponse('profile/profile.phtml', [
            'player' => $player,
            'playtime' => $this->playtimeService->getWeeklyPlaytimeForPlayer($player),
        ]);
    }
}
