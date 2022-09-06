<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Repository\UserRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class CreateUserActionFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): CreateUserAction
    {
        /** @var UserRepository $userRepository */
        $userRepository = $serviceContainer->get(UserRepository::class);

        return new CreateUserAction(
            $userRepository,
        );
    }
}
