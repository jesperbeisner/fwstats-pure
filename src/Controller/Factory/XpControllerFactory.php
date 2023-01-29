<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\XpController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;

final readonly class XpControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): XpController
    {
        return new XpController();
    }
}
