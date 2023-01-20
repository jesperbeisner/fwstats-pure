<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Interface\TranslatorInterface;
use Jesperbeisner\Fwstats\Service\RenderService;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Request;

class RenderServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RenderService
    {
        return new RenderService(
            $container->get(Config::class)->getString('views_directory'),
            $container->get(Config::class)->getAppEnv(),
            $container->get(Request::class),
            $container->get(SessionInterface::class),
            $container->get(TranslatorInterface::class)
        );
    }
}
