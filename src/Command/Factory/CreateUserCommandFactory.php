<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Command\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Command\CreateUserCommand;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

class CreateUserCommandFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): CreateUserCommand
    {
        /** @var CreateUserAction $createUserAction */
        $createUserAction = $container->get(CreateUserAction::class);

        return new CreateUserCommand($createUserAction);
    }
}
