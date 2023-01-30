<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\RaceChangeImageController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;

final class RaceChangeImageControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RaceChangeImageController
    {
        return new RaceChangeImageController(
            $container->get(ConfigRepository::class),
        );
    }
}
