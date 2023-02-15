<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTime;
use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\DTO\Playtime;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Integration\Repository\PlayerActiveSecondRepositoryTest
 */
final class PlayerActiveSecondRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(PlayerActiveSecond $playerActiveSecond): PlayerActiveSecond
    {
        $sql = <<<SQL
            UPDATE players_active_seconds
            SET seconds = :seconds
            WHERE world = :world AND player_id = :playerId AND created = :created
        SQL;

        $this->database->update($sql, [
            'world' => $playerActiveSecond->world->value,
            'playerId' => $playerActiveSecond->playerId,
            'seconds' => $playerActiveSecond->seconds,
            'created' => $playerActiveSecond->created->format('Y-m-d')
        ]);

        $sql = <<<SQL
            INSERT OR IGNORE INTO players_active_seconds (world, player_id, seconds, created)
            VALUES (:world, :playerId, :seconds, :created)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $playerActiveSecond->world->value,
            'playerId' => $playerActiveSecond->playerId,
            'seconds' => $playerActiveSecond->seconds,
            'created' => $playerActiveSecond->created->format('Y-m-d')
        ]);

        return PlayerActiveSecond::withId($id, $playerActiveSecond);
    }

    /**
     * @param array<PlayerActiveSecond> $playerActiveSeconds
     */
    public function insertPlayerActiveSeconds(array $playerActiveSeconds): void
    {
        try {
            $this->database->beginTransaction();

            foreach ($playerActiveSeconds as $playerActiveSecond) {
                $this->insert($playerActiveSecond);
            }

            $this->database->commitTransaction();
        } catch (Exception $e) {
            $this->database->rollbackTransaction();

            throw $e;
        }
    }

    /**
     * @return array<Playtime>
     */
    public function getPlaytimeByWorld(WorldEnum $world): array
    {
        $sql = <<<SQL
            SELECT
                p.world,
                p.name,
                pas1.player_id,
                pas2.seconds - pas1.seconds AS playtime
            FROM
                players_active_seconds AS pas1
            INNER JOIN
                players_active_seconds AS pas2 ON pas1.player_id = pas2.player_id
            LEFT JOIN
                players AS p ON p.player_id = pas1.player_id AND p.world = pas1.world
            WHERE
                pas1.world = :world
                AND pas2.world = :world
                AND pas1.created = :yesterday
                AND pas2.created = :today
                AND pas2.seconds - pas1.seconds > 0
                AND p.name IS NOT NULL
            ORDER BY
                pas2.seconds - pas1.seconds DESC
        SQL;

        /** @var array<array{world: string, name: string, player_id: int, playtime: int}> $result */
        $result = $this->database->select($sql, [
            'world' => $world->value,
            'yesterday' => (new DateTimeImmutable('-1 day'))->format('Y-m-d'),
            'today' => (new DateTimeImmutable())->format('Y-m-d'),
        ]);

        $playtime = [];
        foreach ($result as $row) {
            $playtime[] = new Playtime(
                world: WorldEnum::from($row['world']),
                name: $row['name'],
                playerId: $row['player_id'],
                playtime: $row['playtime'],
            );
        }

        return $playtime;
    }

    /**
     * @return array<string, int|null>
     */
    public function getPlaytimesForPlayer(Player $player, int $days): array
    {
        $sql = "SELECT ";

        for ($i = 1; $i < $days + 2; $i++) {
            $sql .= "(SELECT seconds FROM players_active_seconds WHERE player_id = :playerId and world = :world AND created = :day$i) AS 'day_$i',";
        }

        $sql = rtrim($sql, ',');
        $date = new DateTime('+1 day');

        $params = [
            'playerId' => $player->playerId,
            'world' => $player->world->value,
        ];

        for ($i = 1; $i < $days + 2; $i++) {
            $params["day$i"] = $date->modify('-1 day')->format('Y-m-d');
        }

        /** @var array{array<string, int>} $result */
        $result = $this->database->select($sql, $params);

        return $result[0];
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM players_active_seconds";

        $this->database->delete($sql);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM players_active_seconds WHERE world = :world";

        $this->database->delete($sql, [
            'world' => WorldEnum::AFSRV->value,
        ]);
    }
}
