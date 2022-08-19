<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\PlayerProfessionHistory;

final class PlayerProfessionHistoryRepository extends AbstractRepository
{
    private string $table = 'players_profession_history';

    public function insert(PlayerProfessionHistory $playerProfessionHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, player_id, old_profession, new_profession)
            VALUES (:world, :playerId, :oldProfession, :newProfession)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerProfessionHistory->world->value,
            'playerId' => $playerProfessionHistory->playerId,
            'oldProfession' => $playerProfessionHistory->oldProfession,
            'newProfession' => $playerProfessionHistory->newProfession,
        ]);
    }
}
