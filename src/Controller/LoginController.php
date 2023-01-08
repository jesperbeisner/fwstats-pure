<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Enum\FlashEnum;
use Jesperbeisner\Fwstats\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response;

final readonly class LoginController implements ControllerInterface
{
    public function __construct(
        private SessionInterface $session,
        private UserRepository $userRepository,
    ) {
    }

    public function execute(Request $request): Response
    {
        if ($request->isPost()) {
            $email = $request->getPostParameter('email');
            $password = $request->getPostParameter('password');

            if ($email === null || $password === null) {
                $this->session->setFlash(FlashEnum::ERROR, 'E-Mail und/oder Passwort Feld dürfen nicht leer sein.');

                Response::redirect('/login');
            }

            if (null === $user = $this->userRepository->findOneByEmail($email)) {
                $this->session->setFlash(FlashEnum::ERROR, sprintf('Ein User mit der E-Mail "%s" existiert nicht.', $email));

                return Response::redirect('/login');
            }

            if (password_verify($password, $user->password) === false) {
                $this->session->setFlash(FlashEnum::ERROR, 'Das Passwort stimmt nicht mit dem hinterlegten Passwort überein.');

                return Response::redirect('/login');
            }

            $this->session->setUser($user);
            $this->session->setFlash(FlashEnum::SUCCESS, 'Du wurdest erfolgreich eingeloggt.');

            if (null !== $securityRedirectUri = $this->session->get('security_request_uri')) {
                $this->session->unset('security_request_uri');

                return Response::redirect($securityRedirectUri);
            }

            return Response::redirect('/');
        }

        return Response::html('security/login.phtml');
    }
}
