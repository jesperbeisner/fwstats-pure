<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Exception\RedirectException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ControllerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;
use Jesperbeisner\Fwstats\Stdlib\Response\RedirectResponse;

final readonly class LoginController implements ControllerInterface
{
    public function __construct(
        private Request $request,
        private SessionInterface $session,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(): ResponseInterface
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPostParameter('email');
            $password = $this->request->getPostParameter('password');

            if ($email === null || $password === null) {
                return new RedirectResponse('/login?error=fields-empty');
            }

            if (null === $user = $this->userRepository->findOneByEmail($email)) {
                return new RedirectResponse('/login?error=email-not-found');
            }

            if (password_verify($password, $user->password) === false) {
                return new RedirectResponse('/login?error=wrong-password');
            }

            $this->session->setUser($user);

            return new RedirectResponse('/?login=success');
        }

        return new HtmlResponse('security/login.phtml', [
            'error' => $this->request->getGetParameter('error'),
        ]);
    }
}
