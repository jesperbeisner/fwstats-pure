<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\PlayerProfessionHistory;
use Jesperbeisner\Fwstats\Stdlib\Interface\PlayerInterface;

final class PlayerProfessionHistoryRepository extends AbstractRepository
{
    private string $table = 'players_profession_history';

    public function insert(PlayerProfessionHistory $playerProfessionHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, player_id, old_profession, new_profession, created)
            VALUES (:world, :playerId, :oldProfession, :newProfession, :created)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerProfessionHistory->world->value,
            'playerId' => $playerProfessionHistory->playerId,
            'oldProfession' => $playerProfessionHistory->oldProfession,
            'newProfession' => $playerProfessionHistory->newProfession,
            'created' => $playerProfessionHistory->created->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return PlayerProfessionHistory[]
     */
    public function getProfessionChangesForPlayer(PlayerInterface $player): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world AND player_id = :playerId ORDER BY created DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $player->getWorld()->value, 'playerId' => $player->getPlayerId()]);

        $playerProfessionHistories = [];
        while (false !== $row = $stmt->fetch()) {
            /**
             * @var array{
             *     world: string,
             *     player_id: int,
             *     old_profession: string|null,
             *     new_profession: string|null,
             *     created: string
             * } $row
             */
            $playerProfessionHistories[] = $this->hydratePlayerProfessionHistory($row);
        }

        return $playerProfessionHistories;
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
     *     old_profession: string|null,
     *     new_profession: string|null,
     *     created: string
     * } $row
     */
    private function hydratePlayerProfessionHistory(array $row): PlayerProfessionHistory
    {
        return new PlayerProfessionHistory(
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            oldProfession: $row['old_profession'],
            newProfession: $row['new_profession'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
