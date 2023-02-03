<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\BanAndDeletionChangeController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerStatusHistoryRepository;

final readonly class BanAndDeletionChangeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): BanAndDeletionChangeController
    {
        return new BanAndDeletionChangeController(
            $container->get(PlayerStatusHistoryRepository::class),
        );
    }
}
