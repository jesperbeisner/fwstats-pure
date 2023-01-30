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

    /**
     * @return array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}>
     */
    public function getProfessionChangesByWorld(WorldEnum $worldEnum): array
    {
        $sql = <<<SQL
            SELECT pph.world, players.player_id, players.name, pph.old_profession, pph.new_profession, pph.created
            FROM players_profession_history pph
            INNER JOIN players ON players.player_id = pph.player_id AND players.world = pph.world
            WHERE pph.world = :world
            ORDER BY pph.created DESC
        SQL;

        /** @var array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}> $result */
        $result = $this->database->select($sql, ['world' => $worldEnum->value]);

        return $result;
    }

    /**
     * @return array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}>
     */
    public function getLast15ProfessionChangesByWorld(WorldEnum $worldEnum): array
    {
        $sql = <<<SQL
            SELECT pph.world, players.player_id, players.name, pph.old_profession, pph.new_profession, pph.created
            FROM players_profession_history pph
            INNER JOIN players ON players.player_id = pph.player_id AND players.world = pph.world
            WHERE pph.world = :world
            ORDER BY pph.created
            DESC LIMIT 15
        SQL;

        /** @var array<array{world: string, player_id: int, name: string, old_profession: null|string, new_profession: null|string, created: string}> $result */
        $result = $this->database->select($sql, ['world' => $worldEnum->value]);

        return $result;
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
