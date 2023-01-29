<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\ProfessionChangeControllerTest
 */
final readonly class ProfessionChangeController implements ControllerInterface
{
    public function __construct(
        private PlayerProfessionHistoryRepository $playerProfessionHistoryRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('changes/professions.phtml', [
            'afsrvProfessionChanges' => $this->playerProfessionHistoryRepository->getProfessionChangesByWorld(WorldEnum::AFSRV),
            'chaosProfessionChanges' => $this->playerProfessionHistoryRepository->getProfessionChangesByWorld(WorldEnum::CHAOS),
        ]);
    }
}
