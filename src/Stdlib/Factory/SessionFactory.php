<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Session;
use Psr\Container\ContainerInterface;

final class SessionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): Session
    {
        /** @var UserRepository $userRepository */
        $userRepository = $serviceContainer->get(UserRepository::class);

        return new Session($userRepository);
    }
}
