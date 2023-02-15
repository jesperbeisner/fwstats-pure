<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\ClanNameHistory;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Integration\Repository\ClanNameHistoryRepositoryTest
 */
final class ClanNameHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(ClanNameHistory $clanNameHistory): ClanNameHistory
    {
        $sql = <<<SQL
            INSERT INTO clans_name_history (world, clan_id, old_shortcut, new_shortcut, old_name, new_name, created)
            VALUES (:world, :clanId, :oldShortcut, :newShortcut, :oldName, :newName, :created)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $clanNameHistory->world->value,
            'clanId' => $clanNameHistory->clanId,
            'oldShortcut' => $clanNameHistory->oldShortcut,
            'newShortcut' => $clanNameHistory->newShortcut,
            'oldName' => $clanNameHistory->oldName,
            'newName' => $clanNameHistory->newName,
            'created' => $clanNameHistory->created->format('Y-m-d H:i:s')
        ]);

        return ClanNameHistory::withId($id, $clanNameHistory);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM clans_name_history WHERE world = :world";

        $this->database->delete($sql, ['world' => WorldEnum::AFSRV->value]);
    }
}
