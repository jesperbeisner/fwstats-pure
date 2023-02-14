<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\BanAndDeletionImageController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\ConfigRepository;

final class BanAndDeletionImageControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): BanAndDeletionImageController
    {
        return new BanAndDeletionImageController(
            $container->get(ConfigRepository::class),
        );
    }
}
