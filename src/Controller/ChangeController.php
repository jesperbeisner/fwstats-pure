<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class ChangeController extends AbstractController
{
    public function __construct(
        private readonly PlayerNameHistoryRepository $playerNameHistoryRepository
    ) {
    }

    public function name(): ResponseInterface
    {
        return new HtmlResponse('changes/names.phtml', [
            'afsrvNameChanges' => $this->playerNameHistoryRepository->getNameChangesByWorld(WorldEnum::AFSRV),
            'chaosNameChanges' => $this->playerNameHistoryRepository->getNameChangesByWorld(WorldEnum::CHAOS),
        ]);
    }
}
