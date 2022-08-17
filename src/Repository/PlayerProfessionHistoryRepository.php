<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Repository;

use Jesperbeisner\Fwstats\DTO\PlayersProfessionHistory;

final class PlayerProfessionHistoryRepository extends AbstractRepository
{
    private string $table = 'players_profession_history';

    public function insert(PlayersProfessionHistory $playersProfessionHistory): void
    {
    }
}
