<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Exception;
use Jesperbeisner\Fwstats\Model\Achievement;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Player;

final class AchievementRepository extends AbstractRepository
{
    private string $table = 'achievements';

    public function findByPlayer(Player $player): ?Achievement
    {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE world = :world AND player_id = :playerId");
        $stmt->execute(['world' => $player->world->value, 'playerId' => $player->playerId]);

        if (false === $achievementRow = $stmt->fetch()) {
            return null;
        }

        /** @var array<int|string> $achievementRow */
        return $this->hydrateAchievement($achievementRow);
    }

    public function insert(Achievement $achievement): void
    {
        $sql = <<<SQL
            INSERT INTO $this->table (
                world, player_id, fields_walked, fields_elixir, fields_run,
                fields_run_fast, npc_kills_gold, normal_npc_killed, phase_npc_killed,
                aggressive_npc_killed, invasion_npc_killed, unique_npc_killed, group_npc_killed,
                soul_stones_gained
            ) VALUES (
                :world, :playerId, :fieldsWalked, :fieldsElixir, :fieldsRun,
                :fieldsRunFast, :npcKillsGold, :normalNpcKilled, :phaseNpcKilled,
                :aggressiveNpcKilled, :invasionNpcKilled, :uniqueNpcKilled, :groupNpcKilled,
                :soulStonesGained
            )
        SQL;

        $this->pdo->prepare($sql)->execute([
            'world' => $achievement->world->value,
            'playerId' => $achievement->playerId,
            'fieldsWalked' => $achievement->fieldsWalked,
            'fieldsElixir' => $achievement->fieldsElixir,
            'fieldsRun' => $achievement->fieldsRun,
            'fieldsRunFast' => $achievement->fieldsRunFast,
            'npcKillsGold' => $achievement->npcKillsGold,
            'normalNpcKilled' => $achievement->normalNpcKilled,
            'phaseNpcKilled' => $achievement->phaseNpcKilled,
            'aggressiveNpcKilled' => $achievement->aggressiveNpcKilled,
            'invasionNpcKilled' => $achievement->invasionNpcKilled,
            'uniqueNpcKilled' => $achievement->uniqueNpcKilled,
            'groupNpcKilled' => $achievement->groupNpcKilled,
            'soulStonesGained' => $achievement->soulStonesGained,
        ]);
    }

    /**
     * @param Achievement[] $achievements
     */
    public function insertAchievements(WorldEnum $world, array $achievements): void
    {
        $sql = "DELETE FROM $this->table WHERE world = :world";

        try {
            $this->pdo->beginTransaction();

            $this->pdo->prepare($sql)->execute(['world' => $world->value]);

            foreach ($achievements as $achievement) {
                $this->insert($achievement);
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }

    /**
     * @param array<string, int|string> $row
     */
    private function hydrateAchievement(array $row): Achievement
    {
        return new Achievement(
            world: WorldEnum::from((string) $row['world']),
            playerId: (int) $row['player_id'],
            fieldsWalked: (int) $row['fields_walked'],
            fieldsElixir: (int) $row['fields_elixir'],
            fieldsRun: (int) $row['fields_run'],
            fieldsRunFast: (int) $row['fields_run_fast'],
            npcKillsGold: (int) $row['npc_kills_gold'],
            normalNpcKilled: (int) $row['normal_npc_killed'],
            phaseNpcKilled: (int) $row['phase_npc_killed'],
            aggressiveNpcKilled: (int) $row['aggressive_npc_killed'],
            invasionNpcKilled: (int) $row['invasion_npc_killed'],
            uniqueNpcKilled: (int) $row['unique_npc_killed'],
            groupNpcKilled: (int) $row['group_npc_killed'],
            soulStonesGained: (int) $row['soul_stones_gained'],
        );
    }
}
