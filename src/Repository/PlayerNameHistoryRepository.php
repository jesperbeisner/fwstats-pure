<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;
use Jesperbeisner\Fwstats\Stdlib\Interface\PlayerInterface;

final class PlayerNameHistoryRepository extends AbstractRepository
{
    private string $table = 'players_name_history';

    public function insert(PlayerNameHistory $playerNameHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, player_id, old_name, new_name, created)
            VALUES (:world, :playerId, :oldName, :newName, :created)
        SQL;

        $this->pdo->prepare($sql)->execute([
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
    public function getNameChangesByWorld(WorldEnum $world): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world ORDER BY created DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $world->value]);

        $playerNameHistories = [];
        while (false !== $row = $stmt->fetch()) {
            /**
             * @var array{
             *     world: string,
             *     player_id: int,
             *     old_name: string,
             *     new_name: string,
             *     created: string
             * } $row
             */
            $playerNameHistories[] = $this->hydratePlayerNameHistory($row);
        }

        return $playerNameHistories;
    }

    /**
     * @return PlayerNameHistory[]
     */
    public function getNameChangesForPlayer(PlayerInterface $player): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world AND player_id = :playerId ORDER BY created DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $player->getWorld()->value, 'playerId' => $player->getPlayerId()]);

        $playerNameHistories = [];
        while (false !== $row = $stmt->fetch()) {
            /**
             * @var array{
             *     world: string,
             *     player_id: int,
             *     old_name: string,
             *     new_name: string,
             *     created: string
             * } $row
             */
            $playerNameHistories[] = $this->hydratePlayerNameHistory($row);
        }

        return $playerNameHistories;
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM $this->table";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
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
