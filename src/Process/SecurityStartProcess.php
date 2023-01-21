<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Attribute\TokenRequired;
use Jesperbeisner\Fwstats\Controller\SecurityController;
use Jesperbeisner\Fwstats\Controller\UnauthorizedController;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\StartProcessInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Request;
use ReflectionClass;

final readonly class SecurityStartProcess implements StartProcessInterface
{
    public function __construct(
        private SessionInterface $session,
        private UserRepository $userRepository,
    ) {
    }

    public function run(Request $request): void
    {
        if (null === $controllerClassString = $request->getController()) {
            throw new RuntimeException('Request does not have a controller class string yet, how is this possible?');
        }

        $controllerReflectionClass = new ReflectionClass($controllerClassString);

        // Check if login is required and if a user is logged in
        if ([] !== $controllerReflectionClass->getAttributes(LoginRequired::class)) {
            if ($this->session->getUser() === null) {
                $request->setController(SecurityController::class);
                $this->session->set('security_request_uri', $request->getUri());

                return;
            }
        }

        if ([] !== $controllerReflectionClass->getAttributes(TokenRequired::class)) {
            if (null === $token = $request->getBearerToken()) {
                $request->setController(UnauthorizedController::class);

                return;
            }

            if (null === $user = $this->userRepository->findOneByToken($token)) {
                $request->setController(UnauthorizedController::class);

                return;
            }

            $this->session->setUser($user);
        }
    }
}
