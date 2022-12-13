<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Response\RedirectResponse;

final readonly class LogoutController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        $this->session->destroy();

        return new RedirectResponse('/?logout=success');
    }
}
