<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerXpHistory;

final class PlayerXpHistoryRepository extends AbstractRepository
{
    public function create(PlayerXpHistory $playerXpHistory): void
    {
        $sql = <<<SQL
            UPDATE players_xp_history
            SET end_xp = :endXp
            WHERE world = :world AND player_id = :playerId AND day = :day
        SQL;

        $this->database->update($sql, [
            'endXp' => $playerXpHistory->endXp,
            'world' => $playerXpHistory->world->value,
            'playerId' => $playerXpHistory->playerId,
            'day' => $playerXpHistory->day->format('Y-m-d H:i:s'),
        ]);

        $sql = <<<SQL
            INSERT OR IGNORE INTO players_xp_history (world, player_id, start_xp, end_xp, day)
            VALUES (:world, :playerId, :startXp, :endXp, :day)
        SQL;

        $this->database->insert($sql, [
            'world' => $playerXpHistory->world->value,
            'playerId' => $playerXpHistory->playerId,
            'startXp' => $playerXpHistory->startXp,
            'endXp' => $playerXpHistory->endXp,
            'day' => $playerXpHistory->day->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return array<PlayerXpHistory>
     */
    public function getXpChangesForPlayer(Player $player, int $days = 7): array
    {
        $sql = <<<SQL
            SELECT id, world, player_id, start_xp, end_xp, day
            FROM players_xp_history
            WHERE world = :world AND player_id = :playerId AND day > :day
        SQL;

        /** @var array<array{id: int, world: string, player_id: int, start_xp: int, end_xp: int, day: string}> $result */
        $result = $this->database->select($sql, [
            'world' => $player->world->value,
            'playerId' => $player->playerId,
            'day' => (new DateTimeImmutable("-$days days"))->format('Y-m-d H:i:s'),
        ]);

        $playerXpHistories = [];
        foreach ($result as $row) {
            $playerXpHistories[] = $this->hydratePlayerXpHistory($row);
        }

        return $playerXpHistories;
    }

    /**
     * @param array{id: int, world: string, player_id: int, start_xp: int, end_xp: int, day: string} $row
     */
    private function hydratePlayerXpHistory(array $row): PlayerXpHistory
    {
        return new PlayerXpHistory(
            id: $row['id'],
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            startXp: $row['start_xp'],
            endXp: $row['end_xp'],
            day: new DateTimeImmutable($row['day']),
        );
    }
}
