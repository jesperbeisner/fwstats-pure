<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\IndexController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;

final readonly class IndexControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): IndexController
    {
        return new IndexController(
            $container->get(PlayerRepository::class),
        );
    }
}
