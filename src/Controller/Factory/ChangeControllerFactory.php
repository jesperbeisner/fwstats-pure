<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ChangeController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;

final readonly class ChangeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ChangeController
    {
        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $container->get(PlayerNameHistoryRepository::class);

        return new ChangeController($playerNameHistoryRepository);
    }
}
