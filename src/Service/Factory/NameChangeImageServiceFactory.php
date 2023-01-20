<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerNameHistoryRepository;
use Jesperbeisner\Fwstats\Service\NameChangeImageService;
use Jesperbeisner\Fwstats\Stdlib\Config;

final class NameChangeImageServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): NameChangeImageService
    {
        return new NameChangeImageService(
            $container->get(Config::class),
            $container->get(PlayerNameHistoryRepository::class),
        );
    }
}
