<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ProfessionChangeImageDisplayController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class ProfessionChangeImageDisplayControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ProfessionChangeImageDisplayController
    {
        return new ProfessionChangeImageDisplayController(
            $container->get(Config::class)->getRootDir(),
        );
    }
}
