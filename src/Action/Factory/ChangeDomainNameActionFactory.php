<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Factory;

use Jesperbeisner\Fwstats\Action\ChangeDomainNameAction;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;

class ChangeDomainNameActionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ChangeDomainNameAction
    {
        return new ChangeDomainNameAction(
            $container->get(ConfigRepository::class),
        );
    }
}
