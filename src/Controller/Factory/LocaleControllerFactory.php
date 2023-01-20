<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LocaleController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;

final readonly class LocaleControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LocaleController
    {
        return new LocaleController(
            $container->get(SessionInterface::class),
        );
    }
}
