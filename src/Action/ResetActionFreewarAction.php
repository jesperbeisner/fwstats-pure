<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action;

use Jesperbeisner\Fwstats\Interface\ActionInterface;
use Jesperbeisner\Fwstats\Interface\ActionResultInterface;
use Jesperbeisner\Fwstats\Repository\AchievementRepository;
use Jesperbeisner\Fwstats\Repository\ClanCreatedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanDeletedHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\ClanRepository;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Repository\PlayerClanHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRaceHistoryRepository;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Result\ActionResult;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Unit\Action\ResetActionFreewarActionTest
 */
final readonly class ResetActionFreewarAction implements ActionInterface
{
    public function __construct(
        private AchievementRepository $achievementRepository,
        private ClanCreatedHistoryRepository $clanCreatedHistoryRepository,
        private ClanDeletedHistoryRepository $clanDeletedHistoryRepository,
        private ClanNameHistoryRepository $clanNameHistoryRepository,
        private ClanRepository $clanRepository,
        private PlayerActiveSecondRepository $playerActiveSecondRepository,
        private PlayerClanHistoryRepository $playerClanHistoryRepository,
        private PlayerNameHistoryRepository $playerNameHistoryRepository,
        private PlayerProfessionHistoryRepository $playerProfessionHistoryRepository,
        private PlayerRaceHistoryRepository $playerRaceHistoryRepository,
        private PlayerRepository $playerRepository,
        private PlayerStatusHistoryRepository $playerStatusHistoryRepository,
    ) {
    }

    public function configure(array $data): ActionInterface
    {
        return $this;
    }

    public function run(): ActionResultInterface
    {
        $this->achievementRepository->resetActionFreewar();
        $this->clanCreatedHistoryRepository->resetActionFreewar();
        $this->clanDeletedHistoryRepository->resetActionFreewar();
        $this->clanNameHistoryRepository->resetActionFreewar();
        $this->clanRepository->resetActionFreewar();
        $this->playerActiveSecondRepository->resetActionFreewar();
        $this->playerClanHistoryRepository->resetActionFreewar();
        $this->playerNameHistoryRepository->resetActionFreewar();
        $this->playerProfessionHistoryRepository->resetActionFreewar();
        $this->playerRaceHistoryRepository->resetActionFreewar();
        $this->playerRepository->resetActionFreewar();
        $this->playerStatusHistoryRepository->resetActionFreewar();

        return new ActionResult(ActionResultInterface::SUCCESS, 'text.reset-action-freewar-success');
    }
}
