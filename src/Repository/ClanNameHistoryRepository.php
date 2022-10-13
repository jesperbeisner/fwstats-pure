<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\ClanNameHistory;

final class ClanNameHistoryRepository extends AbstractRepository
{
    public function insert(ClanNameHistory $clanNameHistory): void
    {
        $sql = <<<SQL
            INSERT INTO clans_name_history (world, clan_id, old_shortcut, new_shortcut, old_name, new_name)
            VALUES (:world, :clanId, :oldShortcut, :newShortcut, :oldName, :newName)
        SQL;

        $this->database->insert($sql, [
            'world' => $clanNameHistory->world->value,
            'clanId' => $clanNameHistory->clanId,
            'oldShortcut' => $clanNameHistory->oldShortcut,
            'newShortcut' => $clanNameHistory->newShortcut,
            'oldName' => $clanNameHistory->oldName,
            'newName' => $clanNameHistory->newName,
        ]);
    }
}
