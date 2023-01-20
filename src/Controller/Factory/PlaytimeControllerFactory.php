<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\PlaytimeController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\PlayerActiveSecondRepository;

final readonly class PlaytimeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): PlaytimeController
    {
        return new PlaytimeController(
            $container->get(PlayerActiveSecondRepository::class),
        );
    }
}
