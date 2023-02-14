<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\BanAndDeletionImageDisplayController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class BanAndDeletionImageDisplayControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): BanAndDeletionImageDisplayController
    {
        return new BanAndDeletionImageDisplayController(
            $container->get(Config::class)->getRootDir(),
        );
    }
}
