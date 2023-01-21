<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Action\ChangeDomainNameAction;
use Jesperbeisner\Fwstats\Controller\ChangeDomainNameController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;

final readonly class ChangeDomainNameControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ChangeDomainNameController
    {
        return new ChangeDomainNameController(
            $container->get(ChangeDomainNameAction::class),
            $container->get(SessionInterface::class),
        );
    }
}
