<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Factory;

use Jesperbeisner\Fwstats\Action\CreateUserAction;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;

class CreateUserActionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): CreateUserAction
    {
        return new CreateUserAction(
            $container->get(UserRepository::class),
            $container->get(TranslatorInterface::class),
        );
    }
}
