<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Session;

final class SessionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): Session
    {
        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        return new Session($userRepository);
    }
}
