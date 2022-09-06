<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\SecurityController;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;
use Psr\Container\ContainerInterface;

final class SecurityControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): SecurityController
    {
        /** @var Request $request */
        $request = $serviceContainer->get(Request::class);

        /** @var SessionInterface $session */
        $session = $serviceContainer->get(SessionInterface::class);

        /** @var UserRepository $userRepository */
        $userRepository = $serviceContainer->get(UserRepository::class);

        return new SecurityController(
            $request,
            $session,
            $userRepository,
        );
    }
}
