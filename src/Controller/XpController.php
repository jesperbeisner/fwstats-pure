<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\WorldEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Service\XpService;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class XpController implements ControllerInterface
{
    public function __construct(
        private XpService $xpService,
    ) {
    }

    public function execute(Request $request): Response
    {
        return Response::html('xp/xp.phtml', [
            'afsrvXpChanges' => $this->xpService->getXpChangesForWorld(WorldEnum::AFSRV),
            'chaosXpChanges' => $this->xpService->getXpChangesForWorld(WorldEnum::CHAOS),
        ]);
    }
}
