<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\Model\ClanDeletedHistory;

final class ClanDeletedHistoryRepository extends AbstractRepository
{
    private string $table = 'clans_deleted_history';

    public function insert(ClanDeletedHistory $clanDeletedHistory): void
    {
        $sql = <<<SQL
            INSERT INTO $this->table (world, clan_id, shortcut, name, leader_id, co_leader_id, diplomat_id, war_points)
            VALUES (:world, :clanId, :shortcut, :name, :leaderId, :coLeaderId, :diplomatId, :warPoints)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $clanDeletedHistory->world->value,
            'clanId' => $clanDeletedHistory->clanId,
            'shortcut' => $clanDeletedHistory->shortcut,
            'name' => $clanDeletedHistory->name,
            'leaderId' => $clanDeletedHistory->leaderId,
            'coLeaderId' => $clanDeletedHistory->coLeaderId,
            'diplomatId' => $clanDeletedHistory->diplomatId,
            'warPoints' => $clanDeletedHistory->warPoints,
        ]);
    }
}
