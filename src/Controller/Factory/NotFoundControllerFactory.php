<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\NotFoundController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class NotFoundControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): NotFoundController
    {
        return new NotFoundController();
    }
}
