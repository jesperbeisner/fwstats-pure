<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

final class PlayerRepository extends AbstractRepository
{
    protected const TABLE_NAME = 'players';

    public function deleteAllPlayers(): void
    {
        $this->database->execute("DELETE FROM players");
    }

    public function createPlayer(int $playerId, string $name, string $race, int $clanId, string $profession, int $xp, int $soulXp): void
    {
        $sql = <<<SQL
            INSERT INTO players (playerId, name, race, clanId, profession, xp, soulXp, totalXp)
            VALUES (:playerId, :name, :race, :clanId, :profession, :xp, :soulXp, :totalXp);
        SQL;

        $statement = $this->database->prepare($sql);
        $statement->execute([
            'playerId' => $playerId,
            'name' => $name,
            'race' => $race,
            'clanId' => $clanId,
            'profession' => $profession,
            'xp' => $xp,
            'soulXp' => $soulXp,
            'totalXp' => $xp + $soulXp,
        ]);
    }

    public function findAllOrderedByTotalXp(): array
    {
        $statement = $this->database->getPdo()->query("SELECT * FROM players ORDER BY totalXp DESC");

        return $statement->fetchAll();
    }
}
