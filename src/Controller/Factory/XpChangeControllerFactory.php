<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\XpChangeController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Service\XpService;

final readonly class XpChangeControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): XpChangeController
    {
        return new XpChangeController(
            $container->get(XpService::class),
        );
    }
}
