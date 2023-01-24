<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Action\ChangePasswordAction;
use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Exception\ActionException;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

#[LoginRequired]
final readonly class ChangePasswordController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
        private ChangePasswordAction $changePasswordAction,
    ) {
    }

    public function execute(Request $request): Response
    {
        if (null === $password = $request->getPostParameter('password')) {
            $this->session->setFlash(FlashEnum::ERROR, 'text.no-password-specified');

            return Response::redirect('/admin', 303);
        }

        if (null === $user = $this->session->getUser()) {
            throw new RuntimeException('This should not be possible? Looks like you messed up once again! :^)');
        }

        try {
            $result = $this->changePasswordAction->configure(['user' => $user, 'password' => $password])->run();
        } catch (ActionException $e) {
            $this->session->setFlash(FlashEnum::ERROR, $e->getMessage());

            return Response::redirect('/admin', 303);
        }

        $this->session->setFlash(FlashEnum::SUCCESS, $result->getMessage());

        return Response::redirect('/admin', 303);
    }
}
