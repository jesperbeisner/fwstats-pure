<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\UnauthorizedController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class UnauthorizedControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): UnauthorizedController
    {
        return new UnauthorizedController();
    }
}
