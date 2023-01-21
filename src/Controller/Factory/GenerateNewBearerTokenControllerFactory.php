<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Action\GenerateNewBearerTokenAction;
use Jesperbeisner\Fwstats\Controller\GenerateNewBearerTokenController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;

final readonly class GenerateNewBearerTokenControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): GenerateNewBearerTokenController
    {
        return new GenerateNewBearerTokenController(
            $container->get(SessionInterface::class),
            $container->get(GenerateNewBearerTokenAction::class),
        );
    }
}
