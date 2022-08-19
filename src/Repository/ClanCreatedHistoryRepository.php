<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\ClanCreatedHistory;

final class ClanCreatedHistoryRepository extends AbstractRepository
{
    private string $table = 'clans_created_history';

    public function insert(ClanCreatedHistory $clanCreatedHistory): void
    {
        $sql = <<<SQL
            INSERT INTO $this->table (world, clan_id, shortcut, name, leader_id, co_leader_id, diplomat_id, war_points)
            VALUES (:world, :clanId, :shortcut, :name, :leaderId, :coLeaderId, :diplomatId, :warPoints)
        SQL;

        $this->pdo->prepare($sql)->execute([
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
}
