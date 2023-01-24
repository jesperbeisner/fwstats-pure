<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

/**
 * @see \Jesperbeisner\Fwstats\Tests\Application\ChangeControllerTest
 */
final readonly class ChangeController implements ControllerInterface
{
    public function __construct(
        private PlayerNameHistoryRepository $playerNameHistoryRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('changes/names.phtml', [
            'afsrvNameChanges' => $this->playerNameHistoryRepository->getNameChangesByWorld(WorldEnum::AFSRV),
            'chaosNameChanges' => $this->playerNameHistoryRepository->getNameChangesByWorld(WorldEnum::CHAOS),
        ]);
    }
}
