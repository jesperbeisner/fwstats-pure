<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\DTO\ClanNamingHistory;

final class ClanNamingHistoryRepository extends AbstractRepository
{
    private string $table = 'clans_naming_history';

    public function insert(ClanNamingHistory $clanNamingHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, clan_id, old_shortcut, new_shortcut, old_name, new_name)
            VALUES (:world, :clanId, :oldShortcut, :newShortcut, :oldName, :newName)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $clanNamingHistory->world->value,
            'clanId' => $clanNamingHistory->clanId,
            'oldShortcut' => $clanNamingHistory->oldShortcut,
            'newShortcut' => $clanNamingHistory->newShortcut,
            'oldName' => $clanNamingHistory->oldName,
            'newName' => $clanNamingHistory->newName,
        ]);
    }
}
