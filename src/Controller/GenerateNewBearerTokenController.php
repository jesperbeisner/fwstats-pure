<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Action\GenerateNewBearerTokenAction;
use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Helper\Str;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class GenerateNewBearerTokenController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
        private GenerateNewBearerTokenAction $generateNewBearerTokenAction,
    ) {
    }

    public function execute(Request $request): Response
    {
        if (null === $user = $this->session->getUser()) {
            throw new RuntimeException('This should not be possible? Looks like you messed up once again! :^)');
        }

        $this->generateNewBearerTokenAction->configure(['user' => $user, 'token' => Str::random(32)])->run();

        $this->session->setFlash(FlashEnum::SUCCESS, 'text.new-token-generated-successfully');

        return Response::redirect('/admin', 303);
    }
}
