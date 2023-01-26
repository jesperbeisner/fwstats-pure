<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerRaceHistory;

final class PlayerRaceHistoryRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(PlayerRaceHistory $playerRaceHistory): PlayerRaceHistory
    {
        $sql = <<<SQL
            INSERT INTO players_race_history (world, player_id, old_race, new_race, created)
            VALUES (:world, :playerId, :oldRace, :newRace, :created)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $playerRaceHistory->world->value,
            'playerId' => $playerRaceHistory->playerId,
            'oldRace' => $playerRaceHistory->oldRace,
            'newRace' => $playerRaceHistory->newRace,
            'created' => $playerRaceHistory->created->format('Y-m-d H:i:s')
        ]);

        return PlayerRaceHistory::withId($id, $playerRaceHistory);
    }

    /**
     * @return PlayerRaceHistory[]
     */
    public function getRaceChangesForPlayer(Player $player): array
    {
        $sql = "SELECT id, world, player_id, old_race, new_race, created FROM players_race_history WHERE world = :world AND player_id = :playerId ORDER BY created DESC";

        $result = $this->database->select($sql, [
            'world' => $player->world->value,
            'playerId' => $player->playerId,
        ]);

        $playerRaceHistories = [];
        foreach ($result as $row) {
            /** @var array{id: int, world: string, player_id: int, old_race: string, new_race: string, created: string} $row */
            $playerRaceHistories[] = $this->hydratePlayerRaceHistory($row);
        }

        return $playerRaceHistories;
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM players_race_history";

        $this->database->delete($sql);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM players_race_history WHERE world = :world";

        $this->database->delete($sql, [
            'world' => WorldEnum::AFSRV->value,
        ]);
    }

    /**
     * @param array{id: int, world: string, player_id: int, old_race: string, new_race: string, created: string} $row
     */
    private function hydratePlayerRaceHistory(array $row): PlayerRaceHistory
    {
        return new PlayerRaceHistory(
            id: $row['id'],
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            oldRace: $row['old_race'],
            newRace: $row['new_race'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
