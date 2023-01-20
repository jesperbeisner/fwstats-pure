<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\NameChangeImageController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;

final class NameChangeImageControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): NameChangeImageController
    {
        return new NameChangeImageController(
            $container->get(ConfigRepository::class),
        );
    }
}
