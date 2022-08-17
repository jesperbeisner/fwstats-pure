<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Exception;
use Jesperbeisner\Fwstats\DTO\Clan;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class ClanRepository extends AbstractRepository
{
    private string $table = 'clans';

    /**
     * @return Clan[]
     */
    public function findAllByWorld(WorldEnum $world): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE world = :world");
        $stmt->execute(['world' => $world->value]);

        $clans = [];
        while (false !== $row = $stmt->fetch()) {
            $clans[$row['clan_id']] = new Clan(
                world: WorldEnum::from($row['world']),
                clanId: $row['clan_id'],
                shortcut: $row['shortcut'],
                name: $row['name'],
                leaderId: $row['leader_id'],
                coLeaderId: $row['co_leader_id'],
                diplomatId: $row['diplomat_id'],
                warPoints: $row['war_points'],
            );
        }

        return $clans;
    }

    public function insert(Clan $clan): void
    {
        $sql = <<<SQL
            INSERT INTO $this->table (world, clan_id, shortcut, name, leader_id, co_leader_id, diplomat_id, war_points)
            VALUES (:world, :clanId, :shortcut, :name, :leaderId, :coLeaderId, :diplomatId, :warPoints)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $clan->world->value,
            'clanId' => $clan->clanId,
            'shortcut' => $clan->shortcut,
            'name' => $clan->name,
            'leaderId' => $clan->leaderId,
            'coLeaderId' => $clan->coLeaderId,
            'diplomatId' => $clan->diplomatId,
            'warPoints' => $clan->warPoints,
        ]);
    }

    /**
     * @param Clan[] $clans
     */
    public function insertClans(WorldEnum $world, array $clans): void
    {
        $sql = "DELETE FROM $this->table WHERE world = :world";

        try {
            $this->pdo->beginTransaction();

            $this->pdo->prepare($sql)->execute(['world' => $world->value]);

            foreach ($clans as $clan) {
                $this->insert($clan);
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }
}
