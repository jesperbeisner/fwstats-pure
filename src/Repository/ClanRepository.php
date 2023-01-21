<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class ClanRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    /**
     * @return Clan[]
     */
    public function findAllByWorld(WorldEnum $world): array
    {
        $sql = "SELECT * FROM clans WHERE world = :world";

        $result = $this->database->select($sql, [
            'world' => $world->value,
        ]);

        $clans = [];
        foreach ($result as $row) {
            /** @var array<int|string|null> $row */
            $clans[$row['clan_id']] = $this->hydrateClan($row);
        }

        return $clans;
    }

    public function insert(Clan $clan): void
    {
        $sql = <<<SQL
            INSERT INTO clans (world, clan_id, shortcut, name, leader_id, co_leader_id, diplomat_id, war_points, created)
            VALUES (:world, :clanId, :shortcut, :name, :leaderId, :coLeaderId, :diplomatId, :warPoints, :created)
        SQL;

        $this->database->insert($sql, [
            'world' => $clan->world->value,
            'clanId' => $clan->clanId,
            'shortcut' => $clan->shortcut,
            'name' => $clan->name,
            'leaderId' => $clan->leaderId,
            'coLeaderId' => $clan->coLeaderId,
            'diplomatId' => $clan->diplomatId,
            'warPoints' => $clan->warPoints,
            'created' => $clan->created->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param Clan[] $clans
     */
    public function insertClans(WorldEnum $world, array $clans): void
    {
        $sql = "DELETE FROM clans WHERE world = :world";

        try {
            $this->database->beginTransaction();

            $this->database->delete($sql, [
                'world' => $world->value,
            ]);

            foreach ($clans as $clan) {
                $this->insert($clan);
            }

            $this->database->commitTransaction();
        } catch (Exception $e) {
            $this->database->rollbackTransaction();

            throw $e;
        }
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM clans";

        $this->database->delete($sql);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM clans WHERE world = :world";

        $this->database->delete($sql, [
            'world' => WorldEnum::AFSRV->value,
        ]);
    }

    /**
     * @param array<int|string|null> $row
     */
    private function hydrateClan(array $row): Clan
    {
        return new Clan(
            world: WorldEnum::from((string) $row['world']),
            clanId: (int) $row['clan_id'],
            shortcut: (string) $row['shortcut'],
            name: (string) $row['name'],
            leaderId: (int) $row['leader_id'],
            coLeaderId: (int) $row['co_leader_id'],
            diplomatId: (int) $row['diplomat_id'],
            warPoints: (int) $row['war_points'],
            created: new DateTimeImmutable((string) $row['created']),
        );
    }
}
