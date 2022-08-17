<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\DTO\Clan;
use Jesperbeisner\Fwstats\DTO\Player;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;

class FreewarDumpService implements FreewarDumpServiceInterface
{
    private const ADMINS = ['Sotrax', 'bwoebi', 'Nyrea', 'Andocai'];

    private const PLAYERS_DUMP_URL = 'https://[WORLD].freewar.de/freewar/dump_players.php';
    private const CLANS_DUMP_URL = 'https://[WORLD].freewar.de/freewar/dump_clans.php';
    private const ACHIEVEMENTS_DUMP_URL = 'https://[WORLD].freewar.de/freewar/dump_achieves.php';

    public function getPlayersDump(WorldEnum $world): array
    {
        $players = [];
        foreach ($this->getDump($world, self::PLAYERS_DUMP_URL) as $playerDump) {
            $playerDump = trim($playerDump);
            $player = explode("\t", $playerDump);

            if (in_array($player[1], self::ADMINS, true)) {
                continue;
            }

            $players[(int) $player[0]] = new Player(
                world: $world,
                playerId: (int) $player[0],
                name: $player[1],
                race: $player[3],
                xp: (int) $player[2],
                soulXp: (int) $player[5],
                totalXp: (int) $player[2] + (int) $player[5],
                clanId: $player[4] === '0' ? null : (int) $player[4],
                profession: $player[6] ?? null,
            );
        }

        return $players;
    }

    public function getClansDump(WorldEnum $world): array
    {
        $clans = [];
        foreach ($this->getDump($world, self::CLANS_DUMP_URL) as $clanDump) {
            $clanDump = trim($clanDump);
            $clan = explode("\t", $clanDump);

            $clans[(int) $clan[0]] = new Clan(
                world: $world,
                clanId: (int) $clan[0],
                shortcut: $clan[1],
                name: $clan[2],
                leaderId: (int) $clan[3],
                coLeaderId: (int) $clan[4],
                diplomatId: (int) $clan[5],
                warPoints: (int) $clan[6],
            );
        }

        return $clans;
    }

    public function getAchievementsDump(WorldEnum $world): array
    {
        $achievements = [];
        foreach ($this->getDump($world, self::ACHIEVEMENTS_DUMP_URL) as $achievementDump) {
            $achievementDump = trim($achievementDump);
            $achievement = explode("\t", $achievementDump);

            $playerId = (int) $achievement[0];
            $achievementId = (int) $achievement[1];
            $achievementValue = (int) $achievement[2];

            $achievements[$playerId][$achievementId] = $achievementValue;
        }

        return $achievements;
    }

    /**
     * @return string[]
     */
    private function getDump(WorldEnum $world, string $url): array
    {
        $dumpUrl = str_replace('[WORLD]', $world->value, $url);

        if (false === $dump = file_get_contents($dumpUrl)) {
            throw new RuntimeException("Could not get the dump url '$dumpUrl'.");
        }

        $dump = trim($dump);
        return explode("\n", $dump);
    }
}
