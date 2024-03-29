<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\SearchController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;

final readonly class SearchControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): SearchController
    {
        return new SearchController(
            $container->get(PlayerRepository::class),
        );
    }
}
