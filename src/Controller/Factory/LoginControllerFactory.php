<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\LoginController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Repository\UserRepository;

final readonly class LoginControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): LoginController
    {
        return new LoginController(
            $container->get(SessionInterface::class),
            $container->get(UserRepository::class),
            $container->get(TranslatorInterface::class),
        );
    }
}
