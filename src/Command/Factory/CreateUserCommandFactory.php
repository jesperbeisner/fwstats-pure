<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Command\CreateUserCommand;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

class CreateUserCommandFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): CreateUserCommand
    {
        /** @var CreateUserAction $createUserAction */
        $createUserAction = $serviceContainer->get(CreateUserAction::class);

        return new CreateUserCommand(
            $createUserAction,
        );
    }
}
