<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerRepository;
use Jesperbeisner\Fwstats\Repository\PlayerXpHistoryRepository;
use Jesperbeisner\Fwstats\Service\XpService;

class XpServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): XpService
    {
        return new XpService(
            $container->get(PlayerXpHistoryRepository::class),
            $container->get(PlayerRepository::class),
        );
    }
}
