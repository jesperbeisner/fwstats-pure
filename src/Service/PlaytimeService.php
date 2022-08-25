<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\DTO\Playtime;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;

final class PlaytimeService
{
    public function __construct(
        private readonly PlayerActiveSecondRepository $playerActiveSecondRepository,
    ) {
    }

    /**
     * WTF is this?! o.O
     *
     * @return array{
     *     day_1: Playtime|null,
     *     day_2: Playtime|null,
     *     day_3: Playtime|null,
     *     day_4: Playtime|null,
     *     day_5: Playtime|null,
     *     day_6: Playtime|null,
     *     day_7: Playtime|null,
     * }
     */
    public function getWeeklyPlaytimeForPlayer(Player $player): array
    {
        $result = [];

        $weeklyPlaytime = $this->playerActiveSecondRepository->getWeeklyPlaytimeForPlayer($player);

        if ($weeklyPlaytime['day_1'] !== null && $weeklyPlaytime['day_2'] !== null) {
            $result['day_1'] = new Playtime(
                $player->world,
                $player->name,
                $player->playerId,
                $weeklyPlaytime['day_1'] - $weeklyPlaytime['day_2']
            );
        } else {
            $result['day_1'] = null;
        }

        if ($weeklyPlaytime['day_2'] !== null && $weeklyPlaytime['day_3'] !== null) {
            $result['day_2'] = new Playtime(
                $player->world,
                $player->name,
                $player->playerId,
                $weeklyPlaytime['day_2'] - $weeklyPlaytime['day_3']
            );
        } else {
            $result['day_2'] = null;
        }

        if ($weeklyPlaytime['day_3'] !== null && $weeklyPlaytime['day_4'] !== null) {
            $result['day_3'] = new Playtime(
                $player->world,
                $player->name,
                $player->playerId,
                $weeklyPlaytime['day_3'] - $weeklyPlaytime['day_4']
            );
        } else {
            $result['day_3'] = null;
        }

        if ($weeklyPlaytime['day_4'] !== null && $weeklyPlaytime['day_5'] !== null) {
            $result['day_4'] = new Playtime(
                $player->world,
                $player->name,
                $player->playerId,
                $weeklyPlaytime['day_4'] - $weeklyPlaytime['day_5']
            );
        } else {
            $result['day_4'] = null;
        }

        if ($weeklyPlaytime['day_5'] !== null && $weeklyPlaytime['day_6'] !== null) {
            $result['day_5'] = new Playtime(
                $player->world,
                $player->name,
                $player->playerId,
                $weeklyPlaytime['day_5'] - $weeklyPlaytime['day_6']
            );
        } else {
            $result['day_5'] = null;
        }

        if ($weeklyPlaytime['day_6'] !== null && $weeklyPlaytime['day_7'] !== null) {
            $result['day_6'] = new Playtime(
                $player->world,
                $player->name,
                $player->playerId,
                $weeklyPlaytime['day_6'] - $weeklyPlaytime['day_7']
            );
        } else {
            $result['day_6'] = null;
        }

        if ($weeklyPlaytime['day_7'] !== null && $weeklyPlaytime['day_8'] !== null) {
            $result['day_7'] = new Playtime(
                $player->world,
                $player->name,
                $player->playerId,
                $weeklyPlaytime['day_7'] - $weeklyPlaytime['day_8']
            );
        } else {
            $result['day_7'] = null;
        }

        return $result;
    }
}
