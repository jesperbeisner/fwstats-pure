<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\ClanNameHistory;

final class ClanNameHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
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

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM clans_name_history WHERE world = :world";

        $this->database->delete($sql, [
            'world' => WorldEnum::AFSRV->value,
        ]);
    }
}
