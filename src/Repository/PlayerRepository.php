<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Exception;
use Jesperbeisner\Fwstats\DTO\Player;
use Jesperbeisner\Fwstats\Enum\WorldEnum;

final class PlayerRepository extends AbstractRepository
{
    private string $table = 'players';

    public function findAllByWorldAndOrderedByTotalXp(WorldEnum $world): array
    {
        $sql = "SELECT * FROM $this->table WHERE world = :world ORDER BY total_xp DESC LIMIT 100";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['world' => $world->value]);

        $players = [];
        while (false !== $row = $stmt->fetch()) {
            $players[] = new Player(
                world: WorldEnum::from($row['world']),
                playerId: $row['player_id'],
                name: $row['name'],
                race: $row['race'],
                xp: $row['xp'],
                soulXp: $row['soul_xp'],
                totalXp: $row['total_xp'],
                clanId: $row['clan_id'],
                profession: $row['profession'],
            );
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
            $players[$row['player_id']] = new Player(
                world: $world,
                playerId: $row['player_id'],
                name: $row['name'],
                race: $row['race'],
                xp: $row['xp'],
                soulXp: $row['soul_xp'],
                totalXp: $row['total_xp'],
                clanId: $row['clan_id'],
                profession: $row['profession'],
            );
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
}
