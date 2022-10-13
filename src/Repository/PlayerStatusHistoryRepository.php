<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;

final class PlayerStatusHistoryRepository extends AbstractRepository
{
    public function insert(PlayerStatusHistory $playerStatusHistory): void
    {
        $sql = <<<SQL
            INSERT INTO players_status_history (world, player_id, name, status)
            VALUES (:world, :playerId, :name, :status)
        SQL;

        $this->database->insert($sql, [
            'world' => $playerStatusHistory->world->value,
            'playerId' => $playerStatusHistory->playerId,
            'name' => $playerStatusHistory->name,
            'status' => $playerStatusHistory->status->value,
        ]);
    }
}
