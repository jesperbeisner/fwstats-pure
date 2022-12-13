<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\DTO\Playtime;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Service\PlaytimeService;
use Jesperbeisner\Fwstats\Stdlib\Exception\NotFoundException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final readonly class ProfileController implements ControllerInterface
{
    public function __construct(
        private Request $request,
        private PlayerRepository $playerRepository,
        private PlaytimeService $playtimeService,
        private PlayerNameHistoryRepository $playerNameHistoryRepository,
        private PlayerRaceHistoryRepository $playerRaceHistoryRepository,
        private PlayerProfessionHistoryRepository $playerProfessionHistoryRepository,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        /** @var string $world */
        $world = $this->request->getRouteParameter('world');
        if (null === $world = WorldEnum::tryFrom($world)) {
            return new HtmlResponse('error.phtml', ['message' => '404 - Page not found'], 404);
        }

        /** @var string $playerId */
        $playerId = $this->request->getRouteParameter('id');
        if (!is_numeric($playerId)) {
            return new HtmlResponse('error.phtml', ['message' => '404 - Page not found'], 404);
        }

        if (null === $player = $this->playerRepository->find($world, (int) $playerId)) {
            return new HtmlResponse('error.phtml', ['message' => '404 - Page not found'], 404);
        }

        $weeklyPlaytimes = $this->playtimeService->getPlaytimesForPlayer($player, 7);
        [$totalPlaytime, $averagePlaytime] = $this->getTotalAndAveragePlaytime($player, $weeklyPlaytimes);

        $nameChanges = $this->playerNameHistoryRepository->getNameChangesForPlayer($player);
        $raceChanges = $this->playerRaceHistoryRepository->getRaceChangesForPlayer($player);
        $professionChanges = $this->playerProfessionHistoryRepository->getProfessionChangesForPlayer($player);

        return new HtmlResponse('profile/profile.phtml', [
            'player' => $player,
            'weeklyPlaytimes' => $weeklyPlaytimes,
            'totalPlaytime' => $totalPlaytime,
            'averagePlaytime' => $averagePlaytime,
            'nameChanges' => $nameChanges,
            'raceChanges' => $raceChanges,
            'professionChanges' => $professionChanges,
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

        $average = count($playtimes) - $nullValues === 0 ? 0 : (int) ($total / (count($playtimes) - $nullValues));

        $totalPlaytime = new Playtime($player->world, $player->name, $player->playerId, $total);
        $averagePlaytime = new Playtime($player->world, $player->name, $player->playerId, $average);

        return [$totalPlaytime, $averagePlaytime];
    }
}
