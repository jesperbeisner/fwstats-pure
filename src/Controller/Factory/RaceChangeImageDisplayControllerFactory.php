<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\RaceChangeImageDisplayController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class RaceChangeImageDisplayControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RaceChangeImageDisplayController
    {
        return new RaceChangeImageDisplayController(
            $container->get(Config::class)->getRootDir(),
        );
    }
}
