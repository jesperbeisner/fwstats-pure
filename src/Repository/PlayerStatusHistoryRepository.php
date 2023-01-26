<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;

final class PlayerStatusHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(PlayerStatusHistory $playerStatusHistory): PlayerStatusHistory
    {
        $sql = <<<SQL
            INSERT INTO players_status_history (world, player_id, name, status)
            VALUES (:world, :playerId, :name, :status)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $playerStatusHistory->world->value,
            'playerId' => $playerStatusHistory->playerId,
            'name' => $playerStatusHistory->name,
            'status' => $playerStatusHistory->status->value,
        ]);

        return PlayerStatusHistory::withId($id, $playerStatusHistory);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM players_status_history WHERE world = :world";

        $this->database->delete($sql, ['world' => WorldEnum::AFSRV->value]);
    }
}
