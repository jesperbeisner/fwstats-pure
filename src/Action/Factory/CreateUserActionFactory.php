<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

class CreateUserActionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): CreateUserAction
    {
        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        return new CreateUserAction($userRepository);
    }
}
