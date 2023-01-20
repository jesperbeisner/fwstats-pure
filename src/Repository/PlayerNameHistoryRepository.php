<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\PlayerInterface;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;

final class PlayerNameHistoryRepository extends AbstractRepository
{
    public function insert(PlayerNameHistory $playerNameHistory): void
    {
        $sql = <<<SQL
            INSERT INTO players_name_history (world, player_id, old_name, new_name, created)
            VALUES (:world, :playerId, :oldName, :newName, :created)
        SQL;

        $this->database->insert($sql, [
            'world' => $playerNameHistory->world->value,
            'playerId' => $playerNameHistory->playerId,
            'oldName' => $playerNameHistory->oldName,
            'newName' => $playerNameHistory->newName,
            'created' => $playerNameHistory->created->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return PlayerNameHistory[]
     */
    public function getNameChangesByWorld(WorldEnum $worldEnum): array
    {
        $sql = "SELECT * FROM players_name_history WHERE world = :world ORDER BY created DESC";

        $result = $this->database->select($sql, [
            'world' => $worldEnum->value,
        ]);

        $playerNameHistories = [];
        foreach ($result as $row) {
            /** @var array{world: string, player_id: int, old_name: string, new_name: string, created: string} $row */
            $playerNameHistories[] = $this->hydratePlayerNameHistory($row);
        }

        return $playerNameHistories;
    }

    /**
     * @return PlayerNameHistory[]
     */
    public function getNameChangesForPlayer(PlayerInterface $player): array
    {
        $sql = "SELECT * FROM players_name_history WHERE world = :world AND player_id = :playerId ORDER BY created DESC";

        $result = $this->database->select($sql, [
            'world' => $player->getWorld()->value,
            'playerId' => $player->getPlayerId(),
        ]);

        $playerNameHistories = [];
        foreach ($result as $row) {
            /** @var array{world: string, player_id: int, old_name: string, new_name: string, created: string} $row */
            $playerNameHistories[] = $this->hydratePlayerNameHistory($row);
        }

        return $playerNameHistories;
    }

    /**
     * @return array<PlayerNameHistory>
     */
    public function getLast15NameChangesByWorld(WorldEnum $worldEnum): array
    {
        $sql = "SELECT * FROM players_name_history WHERE world = :world ORDER BY created DESC LIMIT 15";

        $result = $this->database->select($sql, [
            'world' => $worldEnum->value,
        ]);

        $playerNameHistories = [];
        foreach ($result as $row) {
            /** @var array{world: string, player_id: int, old_name: string, new_name: string, created: string} $row */
            $playerNameHistories[] = $this->hydratePlayerNameHistory($row);
        }

        return $playerNameHistories;
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM players_name_history";

        $this->database->delete($sql);
    }

    /**
     * @param array{
     *     world: string,
     *     player_id: int,
     *     old_name: string,
     *     new_name: string,
     *     created: string
     * } $row
     */
    private function hydratePlayerNameHistory(array $row): PlayerNameHistory
    {
        return new PlayerNameHistory(
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            oldName: $row['old_name'],
            newName: $row['new_name'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
