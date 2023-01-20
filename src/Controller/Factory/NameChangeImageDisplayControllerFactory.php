<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\NameChangeImageDisplayController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class NameChangeImageDisplayControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): NameChangeImageDisplayController
    {
        return new NameChangeImageDisplayController(
            $container->get(Config::class)->getRootDir(),
        );
    }
}
