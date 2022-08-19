<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\PlayerRaceHistory;

final class PlayerRaceHistoryRepository extends AbstractRepository
{
    private string $table = 'players_race_history';

    public function insert(PlayerRaceHistory $playerRaceHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, player_id, old_race, new_race)
            VALUES (:world, :playerId, :oldRace, :newRace)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerRaceHistory->world->value,
            'playerId' => $playerRaceHistory->playerId,
            'oldRace' => $playerRaceHistory->oldRace,
            'newRace' => $playerRaceHistory->newRace,
        ]);
    }
}
