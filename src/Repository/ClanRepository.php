<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Clan;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Functional\Repository\ClanRepositoryTest
 */
final class ClanRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(Clan $clan): Clan
    {
        $sql = <<<SQL
            INSERT INTO clans (world, clan_id, shortcut, name, leader_id, co_leader_id, diplomat_id, war_points, created)
            VALUES (:world, :clanId, :shortcut, :name, :leaderId, :coLeaderId, :diplomatId, :warPoints, :created)
        SQL;

        $id = $this->database->insert($sql, [
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

        return Clan::withId($id, $clan);
    }

    /**
     * @return array<Clan>
     */
    public function findAllByWorld(WorldEnum $world): array
    {
        $sql = <<<SQL
            SELECT id, world, clan_id, shortcut, name, leader_id, co_leader_id, diplomat_id, war_points, created
            FROM clans
            WHERE world = :world
        SQL;

        /** @var array<array{id: int, world: string, clan_id: int, shortcut: string, name: string, leader_id: int, co_leader_id: int, diplomat_id: int, war_points: int, created: string}> $result */
        $result = $this->database->select($sql, ['world' => $world->value]);

        $clans = [];
        foreach ($result as $row) {
            $clan = $this->hydrateClan($row);
            $clans[$clan->clanId] = $clan;
        }

        return $clans;
    }

    /**
     * @param array<Clan> $clans
     */
    public function insertClans(WorldEnum $world, array $clans): void
    {
        $sql = "DELETE FROM clans WHERE world = :world";

        try {
            $this->database->beginTransaction();

            $this->database->delete($sql, ['world' => $world->value]);

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

        $this->database->delete($sql, ['world' => WorldEnum::AFSRV->value]);
    }

    /**
     * @param array{id: int, world: string, clan_id: int, shortcut: string, name: string, leader_id: int, co_leader_id: int, diplomat_id: int, war_points: int, created: string} $row
     */
    private function hydrateClan(array $row): Clan
    {
        return new Clan(
            id: $row['id'],
            world: WorldEnum::from($row['world']),
            clanId: $row['clan_id'],
            shortcut: $row['shortcut'],
            name: $row['name'],
            leaderId: $row['leader_id'],
            coLeaderId: $row['co_leader_id'],
            diplomatId: $row['diplomat_id'],
            warPoints: $row['war_points'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
