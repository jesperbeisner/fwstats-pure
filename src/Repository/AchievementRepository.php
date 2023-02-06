<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use DateTimeImmutable;
use Exception;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ResetActionFreewarInterface;
use Jesperbeisner\Fwstats\Model\Achievement;
use Jesperbeisner\Fwstats\Model\Player;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Functional\Repository\AchievementRepositoryTest
 */
final class AchievementRepository extends AbstractRepository implements ResetActionFreewarInterface
{
    public function insert(Achievement $achievement): Achievement
    {
        $sql = <<<SQL
            UPDATE achievements
            SET
                fields_walked = :fieldsWalked,
                fields_elixir = :fieldsElixir,
                fields_run = :fieldsRun,
                fields_run_fast = :fieldsRunFast,
                npc_kills_gold = :npcKillsGold,
                normal_npc_killed = :normalNpcKilled,
                phase_npc_killed = :phaseNpcKilled,
                aggressive_npc_killed = :aggressiveNpcKilled,
                invasion_npc_killed = :invasionNpcKilled,
                unique_npc_killed = :uniqueNpcKilled,
                group_npc_killed = :groupNpcKilled,
                soul_stones_gained = :soulStonesGained
            WHERE world = :world AND player_id = :playerId AND day = :day
        SQL;

        $this->database->update($sql, [
            'world' => $achievement->world->value,
            'playerId' => $achievement->playerId,
            'day' => $achievement->day->format('Y-m-d H:i:s'),
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

        $sql = <<<SQL
            INSERT OR IGNORE INTO achievements (
                world, player_id, fields_walked, fields_elixir, fields_run,
                fields_run_fast, npc_kills_gold, normal_npc_killed, phase_npc_killed,
                aggressive_npc_killed, invasion_npc_killed, unique_npc_killed, group_npc_killed,
                soul_stones_gained, day
            ) VALUES (
                :world, :playerId, :fieldsWalked, :fieldsElixir, :fieldsRun,
                :fieldsRunFast, :npcKillsGold, :normalNpcKilled, :phaseNpcKilled,
                :aggressiveNpcKilled, :invasionNpcKilled, :uniqueNpcKilled, :groupNpcKilled,
                :soulStonesGained, :day
            )
        SQL;

        $id = $this->database->insert($sql, [
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
            'day' => $achievement->day->format('Y-m-d H:i:s'),
        ]);

        return Achievement::withId($id, $achievement);
    }

    public function findByPlayer(Player $player): ?Achievement
    {
        $sql = <<<SQL
            SELECT id, world, player_id, fields_walked, fields_elixir, fields_run, fields_run_fast, npc_kills_gold, normal_npc_killed, phase_npc_killed, aggressive_npc_killed, invasion_npc_killed, unique_npc_killed, group_npc_killed, soul_stones_gained, day
            FROM achievements
            WHERE world = :world AND player_id = :playerId
        SQL;

        /** @var null|array{id: int, world: string, player_id: int, fields_walked: int, fields_elixir: int, fields_run: int, fields_run_fast: int, npc_kills_gold: int, normal_npc_killed: int, phase_npc_killed: int, aggressive_npc_killed: int, invasion_npc_killed: int, unique_npc_killed: int, group_npc_killed: int, soul_stones_gained: int, day: string} $result */
        $result = $this->database->selectOne($sql, ['world' => $player->world->value, 'playerId' => $player->playerId]);

        if ($result === null) {
            return null;
        }

        return $this->hydrateAchievement($result);
    }

    /**
     * @param Achievement[] $achievements
     */
    public function insertAchievements(WorldEnum $world, array $achievements): void
    {
        $sql = "DELETE FROM achievements WHERE world = :world";

        try {
            $this->database->beginTransaction();
            $this->database->delete($sql, ['world' => $world->value]);

            foreach ($achievements as $achievement) {
                $this->insert($achievement);
            }

            $this->database->commitTransaction();
        } catch (Exception $e) {
            $this->database->rollbackTransaction();

            throw $e;
        }
    }

    public function resetActionFreewar(): void
    {
        $sql = "DELETE FROM achievements WHERE world = :world";

        $this->database->delete($sql, ['world' => WorldEnum::AFSRV->value]);
    }

    /**
     * @param array{id: int, world: string, player_id: int, fields_walked: int, fields_elixir: int, fields_run: int, fields_run_fast: int, npc_kills_gold: int, normal_npc_killed: int, phase_npc_killed: int, aggressive_npc_killed: int, invasion_npc_killed: int, unique_npc_killed: int, group_npc_killed: int, soul_stones_gained: int, day: string} $row
     */
    private function hydrateAchievement(array $row): Achievement
    {
        return new Achievement(
            id: $row['id'],
            world: WorldEnum::from($row['world']),
            playerId: $row['player_id'],
            fieldsWalked: $row['fields_walked'],
            fieldsElixir: $row['fields_elixir'],
            fieldsRun: $row['fields_run'],
            fieldsRunFast: $row['fields_run_fast'],
            npcKillsGold: $row['npc_kills_gold'],
            normalNpcKilled: $row['normal_npc_killed'],
            phaseNpcKilled: $row['phase_npc_killed'],
            aggressiveNpcKilled: $row['aggressive_npc_killed'],
            invasionNpcKilled: $row['invasion_npc_killed'],
            uniqueNpcKilled: $row['unique_npc_killed'],
            groupNpcKilled: $row['group_npc_killed'],
            soulStonesGained: $row['soul_stones_gained'],
            day: new DateTimeImmutable($row['day']),
        );
    }
}
