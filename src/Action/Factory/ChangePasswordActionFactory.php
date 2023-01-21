<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Factory;

use Jesperbeisner\Fwstats\Action\ChangePasswordAction;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;

class ChangePasswordActionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ChangePasswordAction
    {
        return new ChangePasswordAction(
            $container->get(UserRepository::class),
        );
    }
}
