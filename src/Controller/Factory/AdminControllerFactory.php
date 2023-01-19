<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\AdminController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class AdminControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): AdminController
    {
        return new AdminController();
    }
}
