<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class LogoutController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {
    }

    public function execute(Request $request): Response
    {
        $this->session->destroy();

        $this->session->setFlash(FlashEnum::SUCCESS, 'text.logout-success');

        return Response::redirect('/');
    }
}
