<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\HomeController;
use Jesperbeisner\Fwstats\Stdlib\Database;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Psr\Container\ContainerInterface;

final class HomeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $serviceName): HomeController
    {
        $container->get(Database::class);
        return new HomeController();
    }
}
