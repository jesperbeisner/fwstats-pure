<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Exception;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\PlayerActiveSecond;

final class PlayerActiveSecondRepository extends AbstractRepository
{
    private string $table = 'players_active_seconds';

    public function insert(PlayerActiveSecond $playerActiveSecond): void
    {
        $sql = <<<SQL
            INSERT INTO $this->table (world, player_id, seconds)
            VALUES (:world, :playerId, :seconds)
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $playerActiveSecond->world->value,
            'playerId' => $playerActiveSecond->playerId,
            'seconds' => $playerActiveSecond->seconds,
        ]);
    }

    /**
     * @param PlayerActiveSecond[]
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
            /** @var array{world: string, player_id: int, seconds: int} $row */
            $playerActiveSeconds[$row['player_id']] = new PlayerActiveSecond(
                world: WorldEnum::from($row['world']),
                playerId: $row['player_id'],
                seconds: $row['seconds'],
            );
        }

        return $playerActiveSeconds;
    }
}
