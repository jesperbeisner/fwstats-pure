<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Process;

use Jesperbeisner\Fwstats\Attribute\LoginRequired;
use Jesperbeisner\Fwstats\Controller\SecurityController;
use Jesperbeisner\Fwstats\Exception\RuntimeException;
use Jesperbeisner\Fwstats\Interface\ProcessInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use ReflectionClass;

final readonly class SecurityProcess implements ProcessInterface
{
    public function __construct(
        private SessionInterface $session,
    ) {
    }

    public function run(Request $request): void
    {
        if (null === $controllerClassString = $request->getController()) {
            throw new RuntimeException('Request does not have a controller class string yet, how is this possible?');
        }

        $controllerReflectionClass = new ReflectionClass($controllerClassString);

        if ([] === $controllerReflectionClass->getAttributes(LoginRequired::class)) {
            return;
        }

        if ($this->session->getUser() === null) {
            $request->setController(SecurityController::class);
            $this->session->set('security_request_uri', $request->getUri());
        }
    }
}
