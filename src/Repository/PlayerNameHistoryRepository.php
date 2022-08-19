<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\PlayerNameHistory;

final class PlayerNameHistoryRepository extends AbstractRepository
{
    private string $table = 'players_name_history';

    public function insert(PlayerNameHistory $playerNameHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, player_id, old_name, new_name)
            VALUES (:world, :playerId, :oldName, :newName)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerNameHistory->world->value,
            'playerId' => $playerNameHistory->playerId,
            'oldName' => $playerNameHistory->oldName,
            'newName' => $playerNameHistory->newName,
        ]);
    }
}
