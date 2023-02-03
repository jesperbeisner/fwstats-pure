<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\BanAndDeletionChangeControllerTest
 */
final readonly class BanAndDeletionChangeController implements ControllerInterface
{
    public function __construct(
        private PlayerStatusHistoryRepository $playerStatusHistoryRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('changes/bans-and-deletions.phtml', [
            'afsrvBansAndDeletions' => $this->playerStatusHistoryRepository->findBansAndDeletionsByWorld(WorldEnum::AFSRV),
            'chaosBansAndDeletions' => $this->playerStatusHistoryRepository->findBansAndDeletionsByWorld(WorldEnum::CHAOS),
        ]);
    }
}
