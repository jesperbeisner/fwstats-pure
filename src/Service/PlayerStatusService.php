<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use Jesperbeisner\Fwstats\Enum\PlayerStatusEnum;
use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Stdlib\Exception\RuntimeException;

final class PlayerStatusService
{
    private const PROFILE = 'https://[WORLD].freewar.de/freewar/internal/fight.php?action=watchuser&act_user_id=[PLAYER_ID]';
    private const NAME_START_NEEDLE = '<p class="maincaption2">Name</p><p class="maindesc1">';
    private const NAME_END_NEEDLE = '</p>';

    public function getStatus(WorldEnum $world, Player $player): PlayerStatusEnum
    {
        $profileUrl = str_replace('[WORLD]', $world->value, self::PROFILE);
        $profileUrl = str_replace('[PLAYER_ID]', (string) ($player->playerId + 100000), $profileUrl);

        if (false === $profile = file_get_contents($profileUrl)) {
            throw new RuntimeException("Could not get profile from player id '$player->playerId' and world '$world->value'.");
        }

        $nameStartPosition = strpos($profile, self::NAME_START_NEEDLE);
        $profile = substr($profile, $nameStartPosition + strlen(self::NAME_START_NEEDLE));

        /** @var int $nameEndPosition */
        $nameEndPosition = strpos($profile, self::NAME_END_NEEDLE);
        $playerName = substr($profile, 0, $nameEndPosition);

        if ($playerName === $player->name) {
            return PlayerStatusEnum::BANNED;
        }

        if ($playerName === $playerName . 'ba' . $player->playerId) {
            return PlayerStatusEnum::DELETED;
        }

        throw new RuntimeException('Unknown player status.');
    }
}
