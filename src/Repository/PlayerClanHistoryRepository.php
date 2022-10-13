<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\PlayerClanHistory;

final class PlayerClanHistoryRepository extends AbstractRepository
{
    public function insert(PlayerClanHistory $playerClanHistory): void
    {
        $sql = <<<SQL
            INSERT INTO players_clan_history (world, player_id, old_clan_id, new_clan_id, old_shortcut, new_shortcut, old_name, new_name)
            VALUES (:world, :playerId, :oldClanId, :newClanId, :oldShortcut, :newShortcut, :oldName, :newName)
        SQL;

        $this->database->insert($sql, [
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
