<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller;

use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Exception\RedirectException;
use Jesperbeisner\Fwstats\Stdlib\Interface\ResponseInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Jesperbeisner\Fwstats\Stdlib\Response\HtmlResponse;

final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly Request $request,
        private readonly SessionInterface $session,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function login(): ResponseInterface
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPostParameter('email');
            $password = $this->request->getPostParameter('password');

            if ($email === null || $password === null) {
                throw new RedirectException('/login?error=fields-empty');
            }

            if (null === $user = $this->userRepository->findOneByEmail($email)) {
                throw new RedirectException('/login?error=email-not-found');
            }

            if (password_verify($password, $user->password) === false) {
                throw new RedirectException('/login?error=wrong-password');
            }

            $this->session->setUser($user);

            throw new RedirectException('/?login=success');
        }

        return new HtmlResponse('security/login.phtml', [
            'error' => $this->request->getGetParameter('error'),
        ]);
    }

    public function logout(): void
    {
        $this->session->destroy();

        throw new RedirectException('/?logout=success');
    }
}
