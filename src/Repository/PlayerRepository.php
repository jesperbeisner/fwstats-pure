<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Stdlib\Exception\DatabaseException;

final class PlayerRepository extends AbstractRepository
{
    public function find(WorldEnum $world, int $playerId): ?Player
    {
        $sql = "SELECT * FROM players WHERE world = :world AND player_id = :playerId";

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
     * @return Player[]
     */
    public function findAllByWorldAndOrderedByTotalXp(WorldEnum $world): array
    {
        $sql = "SELECT * FROM players WHERE world = :world ORDER BY total_xp DESC LIMIT 100";

        $result = $this->database->select($sql, [
            'world' => $world->value,
        ]);

        $players = [];
        foreach ($result as $row) {
            $players[] = $this->hydratePlayer($row);
        }

        return $players;
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
