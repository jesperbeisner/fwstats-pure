<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\ClanCreatedHistory;

final class ClanCreatedHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(ClanCreatedHistory $clanCreatedHistory): void
    {
        $sql = <<<SQL
            INSERT INTO clans_created_history (world, clan_id, shortcut, name, leader_id, co_leader_id, diplomat_id, war_points)
            VALUES (:world, :clanId, :shortcut, :name, :leaderId, :coLeaderId, :diplomatId, :warPoints)
        SQL;

        $this->database->insert($sql, [
            'world' => $clanCreatedHistory->world->value,
            'clanId' => $clanCreatedHistory->clanId,
            'shortcut' => $clanCreatedHistory->shortcut,
            'name' => $clanCreatedHistory->name,
            'leaderId' => $clanCreatedHistory->leaderId,
            'coLeaderId' => $clanCreatedHistory->coLeaderId,
            'diplomatId' => $clanCreatedHistory->diplomatId,
            'warPoints' => $clanCreatedHistory->warPoints,
        ]);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM clans_created_history WHERE world = :world";

        $this->database->delete($sql, [
            'world' => WorldEnum::AFSRV->value,
        ]);
    }
}
