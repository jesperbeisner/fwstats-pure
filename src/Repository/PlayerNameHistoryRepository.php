<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\PlayerNameHistory;

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
            /** @var array<int|string> $row */
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
     * @param array<int|string> $row
     */
    private function hydratePlayerNameHistory(array $row): PlayerNameHistory
    {
        return new PlayerNameHistory(
            world: WorldEnum::from((string) $row['world']),
            playerId: (int) $row['player_id'],
            oldName: (string) $row['old_name'],
            newName: (string) $row['new_name'],
            created: new DateTimeImmutable((string) $row['created']),
        );
    }
}
