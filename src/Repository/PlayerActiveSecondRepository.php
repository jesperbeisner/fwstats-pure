<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTime;
use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\DTO\Playtime;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;
use Jesperbeisner\Fwstats\Stdlib\Exception\DatabaseException;

final class PlayerActiveSecondRepository extends AbstractRepository
{
    private string $table = 'players_active_seconds';

    public function insert(PlayerActiveSecond $playerActiveSecond): void
    {
        $sql = <<<SQL
            UPDATE $this->table
            SET seconds = :seconds
            WHERE world = :world AND player_id = :playerId AND created = :created
        SQL;

        $this->pdo->prepare($sql)->execute([
            'seconds' => $playerActiveSecond->seconds,
            'world' => $playerActiveSecond->world->value,
            'playerId' => $playerActiveSecond->playerId,
            'created' => $playerActiveSecond->created->format('Y-m-d')
        ]);

        $sql = <<<SQL
            INSERT OR IGNORE INTO $this->table (world, player_id, seconds, created)
            VALUES (:world, :playerId, :seconds, :created)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerActiveSecond->world->value,
            'playerId' => $playerActiveSecond->playerId,
            'seconds' => $playerActiveSecond->seconds,
            'created' => $playerActiveSecond->created->format('Y-m-d')
        ]);
    }

    /**
     * @param PlayerActiveSecond[] $playerActiveSeconds
     */
    public function insertPlayerActiveSeconds(array $playerActiveSeconds): void
    {
        try {
            $this->pdo->beginTransaction();

            foreach ($playerActiveSeconds as $playerActiveSecond) {
                $this->insert($playerActiveSecond);
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }

    /**
     * @return PlayerActiveSecond[]
     */
    public function findAllByWorldAndDate(WorldEnum $world, string $created): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world AND created = :created";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $world->value, 'created' => $created]);

        $playerActiveSeconds = [];
        while (false !== $row = $stmt->fetch()) {
            /** @var array{world: string, player_id: int, seconds: int, created: string} $row */
            $playerActiveSeconds[$row['player_id']] = new PlayerActiveSecond(
                world: WorldEnum::from($row['world']),
                playerId: $row['player_id'],
                seconds: $row['seconds'],
                created: new DateTimeImmutable($row['created'])
            );
        }

        return $playerActiveSeconds;
    }

    /**
     * @return Playtime[]
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

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'world' => $world->value,
            'yesterday' => (new DateTimeImmutable('-1 day'))->format('Y-m-d'),
            'today' => (new DateTimeImmutable())->format('Y-m-d'),
        ]);

        $playtime = [];
        while (false !== $row = $stmt->fetch()) {
            /** @var array{world: string, name: string, player_id: int, playtime: int} $row */
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
        for ($i = 1; $i < $days + 1; $i++) {
            $sql .= "(SELECT seconds FROM $this->table WHERE player_id = :playerId and world = :world AND created = :day$i) AS 'day_$i',";
        }

        $sql = rtrim($sql, ',');
        $date = new DateTime('+1 day');

        $params = [
            'playerId' => $player->playerId,
            'world' => $player->world->value,
        ];

        for ($i = 1; $i < $days +1; $i++) {
            $params["day$i"] = $date->modify('-1 day')->format('Y-m-d');
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch();

        if (is_array($result)) {
            return $result;
        }

        throw new DatabaseException('This should never happen!');
    }
}
