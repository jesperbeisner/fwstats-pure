<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerStatusHistory;

final class PlayerStatusHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(PlayerStatusHistory $playerStatusHistory): PlayerStatusHistory
    {
        $sql = <<<SQL
            INSERT INTO players_status_history (world, player_id, name, status, created, deleted, updated)
            VALUES (:world, :playerId, :name, :status, :created, :deleted, :updated)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $playerStatusHistory->world->value,
            'playerId' => $playerStatusHistory->playerId,
            'name' => $playerStatusHistory->name,
            'status' => $playerStatusHistory->status->value,
            'created' => $playerStatusHistory->created->format('Y-m-d H:i:s'),
            'deleted' => null,
            'updated' => $playerStatusHistory->updated->format('Y-m-d H:i:s'),
        ]);

        return PlayerStatusHistory::withId($id, $playerStatusHistory);
    }

    public function update(PlayerStatusHistory $playerStatusHistory): void
    {
        $sql = <<<SQL
            UPDATE players_status_history
            SET deleted = :deleted AND updated = :updated
            WHERE id = :id
        SQL;

        if ($playerStatusHistory->id === null) {
            throw new RuntimeException('This should not be possible? Looks like you messed up once again! :^)');
        }

        $deletedAndUpdated = new DateTimeImmutable();

        $this->database->update($sql, [
            'id' => $playerStatusHistory->id,
            'deleted' => $deletedAndUpdated->format('Y-m-d H:i:s'),
            'updated' => $deletedAndUpdated->format('Y-m-d H:i:s'),
        ]);
    }

    public function findByPlayer(Player $player): ?PlayerStatusHistory
    {
        $sql = <<<SQL
            SELECT id, world, player_id, name, status, created, deleted, updated
            FROM players_status_history
            WHERE world = :world AND player_id = :playerId AND deleted IS NULL
        SQL;

        /** @var null|array{id: int, world: string, player_id: int, name: string, status: string, created: string, deleted: null|string, updated: string} $result */
        $result = $this->database->selectOne($sql, [
            'world' => $player->world->value,
            'playerId' => $player->playerId,
        ]);

        if ($result === null) {
            return null;
        }

        return $this->hydratePlayerStatusHistory($result);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM players_status_history WHERE world = :world";

        $this->database->delete($sql, ['world' => WorldEnum::AFSRV->value]);
    }

    /**
     * @param array{id: int, world: string, player_id: int, name: string, status: string, created: string, deleted: null|string, updated: string} $row
     */
    private function hydratePlayerStatusHistory(array $row): PlayerStatusHistory
    {
        return new PlayerStatusHistory(
            id: $row['id'],
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            name: $row['name'],
            status: PlayerStatusEnum::from($row['status']),
            created: new DateTimeImmutable($row['created']),
            deleted: $row['deleted'] === null ? null : new DateTimeImmutable($row['deleted']),
            updated: new DateTimeImmutable($row['updated']),
        );
    }
}
