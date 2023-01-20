<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Stdlib\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Session;

final class SessionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): Session
    {
        return new Session(
            $container->get(UserRepository::class),
        );
    }
}
