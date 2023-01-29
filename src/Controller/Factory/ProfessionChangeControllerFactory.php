<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ProfessionChangeController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerProfessionHistoryRepository;

final readonly class ProfessionChangeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ProfessionChangeController
    {
        return new ProfessionChangeController(
            $container->get(PlayerProfessionHistoryRepository::class),
        );
    }
}
