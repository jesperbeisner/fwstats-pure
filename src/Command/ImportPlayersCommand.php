<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command;

use Jesperbeisner\Fwstats\Repository\PlayerRepository;

final class ImportPlayersCommand extends AbstractCommand
{
    public static string $name = 'app:import-players';
    public static string $description = "Deletes the old player stats and imports the current stats in the 'players' database table.";

    public function __construct(
        private readonly PlayerRepository $playerRepository,
    ) {
    }

    public function execute(): int
    {
        $this->playerRepository->deleteAllPlayers();

        $playersDump = file_get_contents('https://afsrv.freewar.de/freewar/dump_players.php');
        $playersDump = trim($playersDump);
        $playersDump = explode("\n", $playersDump);

        $amount = 0;
        foreach ($playersDump as $playerDump) {
            $player = explode("\t", $playerDump);

            $playerId = (int) $player[0];
            $name = $player[1];
            $xp = (int) $player[2];
            $race = $player[3];
            $clanId = (int) $player[4];
            $soulXp = (int) $player[5];
            $profession = $player[6] ?? '';

            $this->playerRepository->createPlayer($playerId, $name, $race, $clanId, $profession, $xp, $soulXp);

            $amount++;
        }

        echo "Success: $amount players imported." . PHP_EOL ;

        return self::SUCCESS;
    }
}
