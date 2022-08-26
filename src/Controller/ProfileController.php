<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\DTO\Playtime;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\PlaytimeService;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
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
            throw new NotFoundException();
        }

        /** @var string $playerId */
        $playerId = $this->request->getRouteParameter('id');
        if (!is_numeric($playerId)) {
            throw new NotFoundException();
        }

        if (null === $player = $this->playerRepository->find($world, (int) $playerId)) {
            throw new NotFoundException();
        }

        $weeklyPlaytimes = $this->playtimeService->getPlaytimesForPlayer($player, 7);
        [$totalPlaytime, $averagePlaytime] = $this->getTotalAndAveragePlaytime($player, $weeklyPlaytimes);

        return new HtmlResponse('profile/profile.phtml', [
            'player' => $player,
            'weeklyPlaytimes' => $weeklyPlaytimes,
            'totalPlaytime' => $totalPlaytime,
            'averagePlaytime' => $averagePlaytime,
        ]);
    }

    /**
     * @param array<string, Playtime|null> $playtimes
     * @return array{Playtime, Playtime}
     */
    private function getTotalAndAveragePlaytime(Player $player, array $playtimes): array
    {
        $total = 0;
        $nullValues = 0;

        foreach ($playtimes as $playtime) {
            if ($playtime !== null) {
                $total += $playtime->getHours() * 60 * 60;
                $total += $playtime->getMinutes() * 60;
                $total += $playtime->getSeconds();
            } else {
                $nullValues++;
            }
        }

        $totalPlaytime = new Playtime($player->world, $player->name, $player->playerId, $total);
        $averagePlaytime = new Playtime($player->world, $player->name, $player->playerId, (int) ($total / (count($playtimes) - $nullValues)));

        return [$totalPlaytime, $averagePlaytime];
    }
}
