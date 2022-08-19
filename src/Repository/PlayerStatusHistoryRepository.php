<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;

final class PlayerStatusHistoryRepository extends AbstractRepository
{
    private string $table = 'players_status_history';

    public function insert(PlayerStatusHistory $playerStatusHistory): void
    {
        $sql = <<<SQL
            INSERT INTO $this->table (world, player_id, name, status)
            VALUES (:world, :playerId, :name, :status)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerStatusHistory->world->value,
            'playerId' => $playerStatusHistory->playerId,
            'name' => $playerStatusHistory->name,
            'status' => $playerStatusHistory->status->value,
        ]);
    }
}
