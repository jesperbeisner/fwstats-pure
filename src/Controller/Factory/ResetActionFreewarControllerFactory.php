<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Action\ResetActionFreewarAction;
use Jesperbeisner\Fwstats\Controller\ResetActionFreewarController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;

final readonly class ResetActionFreewarControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ResetActionFreewarController
    {
        return new ResetActionFreewarController(
            $container->get(SessionInterface::class),
            $container->get(ResetActionFreewarAction::class),
        );
    }
}
