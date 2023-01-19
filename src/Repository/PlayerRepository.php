<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Exception\DatabaseException;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Model\Player;

final class PlayerRepository extends AbstractRepository
{
    public function find(WorldEnum $world, int $playerId): ?Player
    {
        $sql = "SELECT * FROM players WHERE world = :world AND player_id = :playerId";

        /** @var array<array{world: string, player_id: string, name: string, race: string, xp: string, soul_xp: string, total_xp: string, clan_id: string, profession: string}> $result */
        $result = $this->database->select($sql, [
            'world' => $world->value,
            'playerId' => $playerId,
        ]);

        if (count($result) === 0) {
            return null;
        }

        if (count($result) > 1) {
            throw new DatabaseException(sprintf('More than 1 player for the id "%s". How is this possible?', $playerId));
        }

        return $this->hydratePlayer($result[0]);
    }

    /**
     * @return array<array{world: string, id: int, name: string, race: string, xp: int, soul_xp: int, total_xp: int, profession: null|string, clan_id: null|int, clan_name: null|string, clan_shortcut: null|string}>
     */
    public function findAllByWorldAndOrderedByTotalXp(WorldEnum $world, int $offset): array
    {
        $sql = <<<SQL
            SELECT players.world, players.id, players.name, players.race, players.xp, players.soul_xp, players.total_xp, players.profession, clans.id AS clan_id, clans.name AS clan_name, clans.shortcut AS clan_shortcut
            FROM players
            LEFT JOIN clans ON clans.clan_id = players.clan_id
            WHERE players.world = :world
            ORDER BY players.total_xp DESC
            LIMIT :offset, 100
        SQL;

        /** @var array<array{world: string, id: int, name: string, race: string, xp: int, soul_xp: int, total_xp: int, profession: null|string, clan_id: null|int, clan_name: null|string, clan_shortcut: null|string}> $result */
        $result = $this->database->select($sql, [
            'world' => $world->value,
            'offset' => $offset,
        ]);

        return $result;
    }

    /**
     * @return Player[]
     */
    public function findAllByWorld(WorldEnum $world): array
    {
        $sql = "SELECT * FROM players WHERE world = :world";

        $result = $this->database->select($sql, [
            'world' => $world->value,
        ]);

        $players = [];
        foreach ($result as $row) {
            /** @var array<int|string|null> $row */
            $players[$row['player_id']] = $this->hydratePlayer($row);
        }

        return $players;
    }

    public function insert(Player $player): void
    {
        $sql = <<<SQL
            INSERT INTO players (world, player_id, name, race, clan_id, profession, xp, soul_xp, total_xp)
            VALUES (:world, :playerId, :name, :race, :clanId, :profession, :xp, :soulXp, :totalXp)
        SQL;

        $this->database->insert($sql, [
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
    }

    /**
     * @param Player[] $players
     */
    public function insertPlayers(WorldEnum $world, array $players): void
    {
        $sql = "DELETE FROM players WHERE world = :world";

        try {
            $this->database->beginTransaction();

            $this->database->delete($sql, [
                'world' => $world->value,
            ]);

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
     * @return Player[]
     */
    public function getTop50PlayersByWorld(WorldEnum $world): array
    {
        $sql = "SELECT * FROM players WHERE world = :world ORDER BY total_xp DESC LIMIT 50";

        $result = $this->database->select($sql, [
            'world' => $world->value,
        ]);

        $players = [];
        foreach ($result as $row) {
            /** @var array<int|string|null> $row */
            $players[] = $this->hydratePlayer($row);
        }

        return $players;
    }

    public function getMaxAmountOfPlayersInSingleWorld(): int
    {
        $sql = "SELECT COUNT(id) AS amount, world FROM players GROUP BY world ORDER BY COUNT(id) DESC LIMIT 1";

        /** @var array<array{amount: int, world: string}> $result */
        $result = $this->database->select($sql);

        if (count($result) !== 1) {
            throw new RuntimeException('How did this query return more than one result?');
        }

        return $result[0]['amount'];
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM players";

        $this->database->delete($sql);
    }

    /**
     * @param array<int|string|null> $row
     */
    private function hydratePlayer(array $row): Player
    {
        return new Player(
            world: WorldEnum::from((string) $row['world']),
            playerId: (int) $row['player_id'],
            name: (string) $row['name'],
            race: (string) $row['race'],
            xp: (int) $row['xp'],
            soulXp: (int) $row['soul_xp'],
            totalXp: (int) $row['total_xp'],
            clanId: $row['clan_id'] === null ? null : (int) $row['clan_id'],
            profession: $row['profession'] === null ? null : (string) $row['profession'],
            created: new DateTimeImmutable((string) $row['created']),
        );
    }
}
