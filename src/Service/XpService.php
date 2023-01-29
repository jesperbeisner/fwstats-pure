<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service;

use DateTimeImmutable;
use Jesperbeisner\Fwstats\Model\Player;
use Jesperbeisner\Fwstats\Model\PlayerXpHistory;
use Jesperbeisner\Fwstats\Repository\PlayerXpHistoryRepository;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Service\XpServiceTest
 */
final readonly class XpService
{
    public function __construct(
        private PlayerXpHistoryRepository $playerXpHistoryRepository,
    ) {
    }

    /**
     * @param positive-int $days
     * @return array<string, null|PlayerXpHistory>
     */
    public function getXpChangesForPlayer(Player $player, int $days): array
    {
        $xpChanges = [];
        $xpChanges[(new DateTimeImmutable())->format('Y-m-d')] = null;

        for ($i = 1; $i < $days; $i++) {
            $xpChanges[(new DateTimeImmutable("-$i days"))->format('Y-m-d')] = null;
        }

        foreach ($this->playerXpHistoryRepository->getXpChangesForPlayer($player, $days) as $playerXpHistory) {
            $xpChanges[$playerXpHistory->day->format('Y-m-d')] = $playerXpHistory;
        }

        return $xpChanges;
    }
}
