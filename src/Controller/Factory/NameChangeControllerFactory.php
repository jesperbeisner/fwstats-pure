<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\NameChangeController;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class NameChangeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceContainer, string $serviceName): NameChangeController
    {
        /** @var PlayerNameHistoryRepository $playerNameHistoryRepository */
        $playerNameHistoryRepository = $serviceContainer->get(PlayerNameHistoryRepository::class);

        return new NameChangeController($playerNameHistoryRepository);
    }
}
