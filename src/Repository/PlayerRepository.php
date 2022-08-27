<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class PlayerRepository extends AbstractRepository
{
    private string $table = 'players';

    public function find(WorldEnum $world, int $playerId): ?Player
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world AND player_id = :playerId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $world->value, 'playerId' => $playerId]);

        /** @var false|array<int|string|null> $playerData */
        $playerData = $stmt->fetch();

        if ($playerData === false) {
            return null;
        }

        return $this->hydratePlayer($playerData);
    }

    /**
     * @return Player[]
     */
    public function findAllByWorldAndOrderedByTotalXp(WorldEnum $world): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world ORDER BY total_xp DESC LIMIT 100";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $world->value]);

        $players = [];
        while (false !== $row = $stmt->fetch()) {
            /** @var array<int|string|null> $row */
            $players[] = $this->hydratePlayer($row);
        }

        return $players;
    }

    /**
     * @return Player[]
     */
    public function findAllByWorld(WorldEnum $world): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE world = :world");
        $stmt->execute(['world' => $world->value]);

        $players = [];
        while (false !== $row = $stmt->fetch()) {
            /** @var array<int|string|null> $row */
            $players[$row['player_id']] = $this->hydratePlayer($row);
        }

        return $players;
    }

    public function insert(Player $player): void
    {
        $sql = <<<SQL
            INSERT INTO $this->table (world, player_id, name, race, clan_id, profession, xp, soul_xp, total_xp)
            VALUES (:world, :playerId, :name, :race, :clanId, :profession, :xp, :soulXp, :totalXp)
        SQL;

        $this->pdo->prepare($sql)->execute([
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
        $sql = "DELETE FROM $this->table WHERE world = :world";

        try {
            $this->pdo->beginTransaction();

            $this->pdo->prepare($sql)->execute(['world' => $world->value]);

            foreach ($players as $player) {
                $this->insert($player);
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }

    /**
     * @return Player[]
     */
    public function getTop50PlayersByWorld(WorldEnum $world): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world ORDER BY total_xp DESC LIMIT 50";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $world->value]);

        $players = [];
        while (false !== $row = $stmt->fetch()) {
            /** @var array<int|string|null> $row */
            $players[] = $this->hydratePlayer($row);
        }

        return $players;
    }

    public function deleteAll(): void
    {
        $sql = "DELETE FROM $this->table";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
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
