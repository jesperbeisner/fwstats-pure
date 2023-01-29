<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Player;

final class PlayerRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(Player $player): Player
    {
        $sql = <<<SQL
            INSERT INTO players (world, player_id, name, race, clan_id, profession, xp, soul_xp, total_xp)
            VALUES (:world, :playerId, :name, :race, :clanId, :profession, :xp, :soulXp, :totalXp)
        SQL;

        $id = $this->database->insert($sql, [
            'world' => $player->world->value,
            'playerId' => $player->playerId,
            'name' => $player->name,
            'race' => $player->race,
            'clanId' => $player->clanId,
            'profession' => $player->profession,
            'xp' => $player->xp,
            'soulXp' => $player->soulXp,
            'totalXp' => $player->xp + $player->soulXp,
        ]);

        return Player::withId($id, $player);
    }

    public function find(WorldEnum $world, int $playerId): ?Player
    {
        $sql = <<<SQL
            SELECT id, world, player_id, name, race, clan_id, profession, xp, soul_xp, total_xp, created
            FROM players
            WHERE world = :world AND player_id = :playerId
        SQL;

        /** @var null|array{id: int, world: string, player_id: int, name: string, race: string, clan_id: null|int, profession: null|string, xp: int, soul_xp: int, total_xp: int, created: string} $result */
        $result = $this->database->selectOne($sql, ['world' => $world->value, 'playerId' => $playerId]);

        if ($result === null) {
            return null;
        }

        return $this->hydratePlayer($result);
    }

    /**
     * @return array<array{id: int, world: string, player_id: int, name: string, race: string, xp: int, soul_xp: int, total_xp: int, profession: null|string, clan_id: null|int, clan_name: null|string, clan_shortcut: null|string}>
     */
    public function findAllByWorldAndOrderedByTotalXp(WorldEnum $world, int $offset): array
    {
        $sql = <<<SQL
            SELECT players.id, players.world, players.player_id, players.name, players.race, players.xp, players.soul_xp, players.total_xp, players.profession, clans.id AS clan_id, clans.name AS clan_name, clans.shortcut AS clan_shortcut
            FROM players
            LEFT JOIN clans ON clans.clan_id = players.clan_id AND clans.world = players.world
            WHERE players.world = :world
            ORDER BY players.total_xp DESC
            LIMIT :offset, 100
        SQL;

        /** @var array<array{id: int, world: string, player_id: int, name: string, race: string, xp: int, soul_xp: int, total_xp: int, profession: null|string, clan_id: null|int, clan_name: null|string, clan_shortcut: null|string}> $result */
        $result = $this->database->select($sql, ['world' => $world->value, 'offset' => $offset]);

        return $result;
    }

    /**
     * @return array<Player>
     */
    public function findAllByWorld(WorldEnum $world): array
    {
        $sql = <<<SQL
            SELECT id, world, player_id, name, race, clan_id, profession, xp, soul_xp, total_xp, created
            FROM players
            WHERE world = :world
        SQL;

        /** @var array<array{id: int, world: string, player_id: int, name: string, race: string, clan_id: null|int, profession: null|string, xp: int, soul_xp: int, total_xp: int, created: string}> $result */
        $result = $this->database->select($sql, ['world' => $world->value]);

        $players = [];
        foreach ($result as $row) {
            $players[$row['player_id']] = $this->hydratePlayer($row);
        }

        return $players;
    }

    /**
     * @param array<Player> $players
     */
    public function insertPlayers(WorldEnum $world, array $players): void
    {
        $sql = "DELETE FROM players WHERE world = :world";

        try {
            $this->database->beginTransaction();

            $this->database->delete($sql, ['world' => $world->value]);

            foreach ($players as $player) {
                $this->insert($player);
            }

            $this->database->commitTransaction();
        } catch (Exception $e) {
            $this->database->rollbackTransaction();

            throw $e;
        }
    }

    /**
     * @return array<Player>
     */
    public function getTop50PlayersByWorld(WorldEnum $world): array
    {
        $sql = <<<SQL
            SELECT id, world, player_id, name, race, clan_id, profession, xp, soul_xp, total_xp, created
            FROM players
            WHERE world = :world
            ORDER BY total_xp DESC
            LIMIT 50
        SQL;

        /** @var array<array{id: int, world: string, player_id: int, name: string, race: string, clan_id: null|int, profession: null|string, xp: int, soul_xp: int, total_xp: int, created: string}> $result */
        $result = $this->database->select($sql, ['world' => $world->value]);

        $players = [];
        foreach ($result as $row) {
            $players[] = $this->hydratePlayer($row);
        }

        return $players;
    }

    public function getMaxAmountOfPlayersInSingleWorld(): int
    {
        $sql = "SELECT COUNT(id) AS amount, world FROM players GROUP BY world ORDER BY COUNT(id) DESC LIMIT 1";

        /** @var null|array{amount: int, world: string} $result */
        $result = $this->database->selectOne($sql);

        if ($result === null) {
            return 0;
        }

        return $result['amount'];
    }

    /**
     * @return array<array{world: string, id: int, name: string, race: string, xp: int, soul_xp: int, total_xp: int, profession: null|string, clan_id: null|int, clan_name: null|string, clan_shortcut: null|string}>
     */
    public function searchPlayers(string $query): array
    {
        $sql = <<<SQL
            SELECT players.world, players.id, players.name, players.race, players.xp, players.soul_xp, players.total_xp, players.profession, clans.id AS clan_id, clans.name AS clan_name, clans.shortcut AS clan_shortcut
            FROM players
            LEFT JOIN clans ON clans.clan_id = players.clan_id AND clans.world = players.world
            WHERE players.name LIKE :query
            ORDER BY players.total_xp DESC
        SQL;

        /** @var array<array{world: string, id: int, name: string, race: string, xp: int, soul_xp: int, total_xp: int, profession: null|string, clan_id: null|int, clan_name: null|string, clan_shortcut: null|string}> $result */
        $result = $this->database->select($sql, [
            'query' => '%' . $query . '%',
        ]);

        return $result;
    }

    /**
     * @return array<array{player_id: int, name: string, xp: int, soul_xp: int, total_xp: int, xp_changes: int}>
     */
    public function getPlayerXpChangesForWorld(WorldEnum $worldEnum): array
    {
        $sql = <<<SQL
            SELECT players.player_id, players.name, players.xp, players.soul_xp, players.total_xp, players_xp_history.start_xp, players_xp_history.end_xp, players_xp_history.end_xp - players_xp_history.start_xp AS xp_changes
            FROM players
            INNER JOIN players_xp_history ON players.player_id = players_xp_history.player_id AND players_xp_history.world = players.world
            WHERE players.world = :world AND day = :day AND players_xp_history.end_xp - players_xp_history.start_xp != 0
            ORDER BY players_xp_history.end_xp - players_xp_history.start_xp DESC
        SQL;

        /** @var array<array{player_id: int, name: string, xp: int, soul_xp: int, total_xp: int, start_xp: int, end_xp: int, xp_changes: int}> $result */
        $result = $this->database->select($sql, [
            'world' => $worldEnum->value,
            'day' => (new DateTimeImmutable())->setTime(0, 0, 0)->format('Y-m-d H:i:s'),
        ]);

        return $result;
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM players";

        $this->database->delete($sql);
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM players WHERE world = :world";

        $this->database->delete($sql, [
            'world' => WorldEnum::AFSRV->value,
        ]);
    }

    /**
     * @param array{id: int, world: string, player_id: int, name: string, race: string, clan_id: null|int, profession: null|string, xp: int, soul_xp: int, total_xp: int, created: string} $row
     */
    private function hydratePlayer(array $row): Player
    {
        return new Player(
            id: $row['id'],
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            name: $row['name'],
            race: $row['race'],
            xp: $row['xp'],
            soulXp: $row['soul_xp'],
            totalXp: $row['total_xp'],
            clanId: $row['clan_id'],
            profession: $row['profession'],
            created: new DateTimeImmutable($row['created']),
        );
    }
}
