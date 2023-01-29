<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\NameChangeController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;

final readonly class NameChangeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): NameChangeController
    {
        return new NameChangeController(
            $container->get(PlayerNameHistoryRepository::class),
        );
    }
}
