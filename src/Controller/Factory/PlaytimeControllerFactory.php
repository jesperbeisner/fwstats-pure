<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\PlaytimeController;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class PlaytimeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlaytimeController
    {
        /** @var PlayerActiveSecondRepository $playerActiveSecondRepository */
        $playerActiveSecondRepository = $container->get(PlayerActiveSecondRepository::class);

        return new PlaytimeController($playerActiveSecondRepository);
    }
}
