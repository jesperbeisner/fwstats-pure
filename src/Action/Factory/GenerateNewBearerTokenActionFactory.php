<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Action\Factory;

use Jesperbeisner\Fwstats\Action\GenerateNewBearerTokenAction;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;

class GenerateNewBearerTokenActionFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): GenerateNewBearerTokenAction
    {
        return new GenerateNewBearerTokenAction(
            $container->get(UserRepository::class),
        );
    }
}
