<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Action\ResetActionFreewarAction;
use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class ResetActionFreewarController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
        private ResetActionFreewarAction $resetActionFreewarAction,
    ) {
    }

    public function execute(Request $request): Response
    {
        $result = $this->resetActionFreewarAction->run();

        $this->session->setFlash(FlashEnum::SUCCESS, $result->getMessage());

        return Response::redirect('/admin', 303);
    }
}
