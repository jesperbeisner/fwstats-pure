<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\DTO\PlayersRaceHistory;

final class PlayerRaceHistoryRepository extends AbstractRepository
{
    private string $table = 'players_race_history';

    public function insert(PlayersRaceHistory $playersRaceHistory): void
    {
    }
}
