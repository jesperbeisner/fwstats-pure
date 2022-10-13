<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ChangeController;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class ChangeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ChangeController
    {
        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $container->get(PlayerNameHistoryRepository::class);

        return new ChangeController($playerNameHistoryRepository);
    }
}
