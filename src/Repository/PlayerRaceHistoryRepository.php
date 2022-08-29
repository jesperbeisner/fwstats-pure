<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\PlayerRaceHistory;
use Jesperbeisner\Fwstats\Stdlib\Interface\PlayerInterface;

final class PlayerRaceHistoryRepository extends AbstractRepository
{
    private string $table = 'players_race_history';

    public function insert(PlayerRaceHistory $playerRaceHistory): void
    {
        $sql = <<<SQL
            INSERT INTO {$this->table} (world, player_id, old_race, new_race, created)
            VALUES (:world, :playerId, :oldRace, :newRace, :created)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerRaceHistory->world->value,
            'playerId' => $playerRaceHistory->playerId,
            'oldRace' => $playerRaceHistory->oldRace,
            'newRace' => $playerRaceHistory->newRace,
            'created' => $playerRaceHistory->created->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @return PlayerRaceHistory[]
     */
    public function getRaceChangesForPlayer(PlayerInterface $player): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world AND player_id = :playerId ORDER BY created DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $player->getWorld()->value, 'playerId' => $player->getPlayerId()]);

        $playerRaceHistories = [];
        while (false !== $row = $stmt->fetch()) {
            /**
             * @var array{
             *     world: string,
             *     player_id: int,
             *     old_race: string,
             *     new_race: string,
             *     created: string
             * } $row
             */
            $playerRaceHistories[] = $this->hydratePlayerRaceHistory($row);
        }

        return $playerRaceHistories;
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
     *     old_race: string,
     *     new_race: string,
     *     created: string
     * } $row
     */
    private function hydratePlayerRaceHistory(array $row): PlayerRaceHistory
    {
        return new PlayerRaceHistory(
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            oldRace: $row['old_race'],
            newRace: $row['new_race'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
