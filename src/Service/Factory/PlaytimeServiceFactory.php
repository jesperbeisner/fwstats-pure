<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Service\PlaytimeService;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

class PlaytimeServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlaytimeService
    {
        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $container->get(PlayerActiveSecondRepository::class);

        return new PlaytimeService($playerActiveSecondRepository);
    }
}
