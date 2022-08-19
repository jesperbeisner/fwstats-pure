<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\PlayerClanHistory;

final class PlayerClanHistoryRepository extends AbstractRepository
{
    private string $table = 'players_clan_history';

    public function insert(PlayerClanHistory $playerClanHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, player_id, old_clan_id, new_clan_id, old_shortcut, new_shortcut, old_name, new_name)
            VALUES (:world, :playerId, :oldClanId, :newClanId, :oldShortcut, :newShortcut, :oldName, :newName)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerClanHistory->world->value,
            'playerId' => $playerClanHistory->playerId,
            'oldClanId' => $playerClanHistory->oldClanId,
            'newClanId' => $playerClanHistory->newClanId,
            'oldShortcut' => $playerClanHistory->oldShortcut,
            'newShortcut' => $playerClanHistory->newShortcut,
            'oldName' => $playerClanHistory->oldName,
            'newName' => $playerClanHistory->newName,
        ]);
    }
}
