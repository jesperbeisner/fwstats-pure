<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\ClanNameHistory;

final class ClanNameHistoryRepository extends AbstractRepository
{
    private string $table = 'clans_name_history';

    public function insert(ClanNameHistory $clanNameHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, clan_id, old_shortcut, new_shortcut, old_name, new_name)
            VALUES (:world, :clanId, :oldShortcut, :newShortcut, :oldName, :newName)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $clanNameHistory->world->value,
            'clanId' => $clanNameHistory->clanId,
            'oldShortcut' => $clanNameHistory->oldShortcut,
            'newShortcut' => $clanNameHistory->newShortcut,
            'oldName' => $clanNameHistory->oldName,
            'newName' => $clanNameHistory->newName,
        ]);
    }
}
