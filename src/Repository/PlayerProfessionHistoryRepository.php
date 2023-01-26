<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerProfessionHistory;

final class PlayerProfessionHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(PlayerProfessionHistory $playerProfessionHistory): PlayerProfessionHistory
    {
        $sql = <<<SQL
            INSERT INTO players_profession_history (world, player_id, old_profession, new_profession, created)
            VALUES (:world, :playerId, :oldProfession, :newProfession, :created)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $playerProfessionHistory->world->value,
            'playerId' => $playerProfessionHistory->playerId,
            'oldProfession' => $playerProfessionHistory->oldProfession,
            'newProfession' => $playerProfessionHistory->newProfession,
            'created' => $playerProfessionHistory->created->format('Y-m-d H:i:s'),
        ]);

        return PlayerProfessionHistory::withId($id, $playerProfessionHistory);
    }

    /**
     * @return array<PlayerProfessionHistory>
     */
    public function getProfessionChangesForPlayer(Player $player): array
    {
        $sql = <<<SQL
            SELECT id, world, player_id, old_profession, new_profession, created
            FROM players_profession_history
            WHERE world = :world AND player_id = :playerId
            ORDER BY created DESC
        SQL;

        /** @var array<array{id: int, world: string, player_id: int, old_profession: string|null, new_profession: string|null, created: string}> $result */
        $result = $this->database->select($sql, ['world' => $player->world->value, 'playerId' => $player->playerId]);

        $playerProfessionHistories = [];
        foreach ($result as $row) {
            $playerProfessionHistories[] = $this->hydratePlayerProfessionHistory($row);
        }

        return $playerProfessionHistories;
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM players_profession_history";

        $this->database->delete($sql);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM players_profession_history WHERE world = :world";

        $this->database->delete($sql, ['world' => WorldEnum::AFSRV->value]);
    }

    /**
     * @param array{id: int, world: string, player_id: int, old_profession: string|null, new_profession: string|null, created: string} $row
     */
    private function hydratePlayerProfessionHistory(array $row): PlayerProfessionHistory
    {
        return new PlayerProfessionHistory(
            id: $row['id'],
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            oldProfession: $row['old_profession'],
            newProfession: $row['new_profession'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
