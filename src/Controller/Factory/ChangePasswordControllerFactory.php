<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Action\ChangePasswordAction;
use Jesperbeisner\Fwstats\Controller\ChangePasswordController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;

final readonly class ChangePasswordControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ChangePasswordController
    {
        return new ChangePasswordController(
            $container->get(SessionInterface::class),
            $container->get(ChangePasswordAction::class),
        );
    }
}
