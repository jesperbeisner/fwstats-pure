<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\PlayerCreatedHistory;

final class PlayerCreatedHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(PlayerCreatedHistory $playerCreatedHistory): PlayerCreatedHistory
    {
        $sql = <<<SQL
            INSERT INTO players_created_history (world, player_id, name, created)
            VALUES (:world, :playerId, :name, :created)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $playerCreatedHistory->world->value,
            'playerId' => $playerCreatedHistory->playerId,
            'name' => $playerCreatedHistory->name,
            'created' => $playerCreatedHistory->created->format('Y-m-d H:i:s'),
        ]);

        return PlayerCreatedHistory::withId($id, $playerCreatedHistory);
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM players_created_history";

        $this->database->delete($sql);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM players_created_history WHERE world = :world";

        $this->database->delete($sql, [
            'world' => WorldEnum::AFSRV->value,
        ]);
    }
}
