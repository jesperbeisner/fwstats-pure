<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class LoginController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
        private UserRepository $userRepository,
        private TranslatorInterface $translator,
    ) {
    }

    public function execute(Request $request): Response
    {
        if ($request->isPost()) {
            $username = $request->getPostParameter('username');
            $password = $request->getPostParameter('password');

            if ($username === null || $password === null) {
                $this->session->setFlash(FlashEnum::ERROR, 'text.username-and-password-cant-be-empty');

                return Response::redirect('/login');
            }

            if (null === $user = $this->userRepository->findOneByUsername($username)) {
                $this->session->setFlash(FlashEnum::ERROR, $this->translator->translate('text.user-with-username-does-not-exist', ['%USERNAME%' => $username]));

                return Response::redirect('/login');
            }

            if (password_verify($password, $user->password) === false) {
                $this->session->setFlash(FlashEnum::ERROR, 'text.password-does-not-match');

                return Response::redirect('/login');
            }

            $this->session->setUser($user);
            $this->session->setFlash(FlashEnum::SUCCESS, 'text.login-success');

            if (null !== $securityRedirectUri = $this->session->get('security_request_uri')) {
                $this->session->unset('security_request_uri');

                return Response::redirect((string) $securityRedirectUri);
            }

            return Response::redirect('/admin');
        }

        return Response::html('security/login.phtml');
    }
}
