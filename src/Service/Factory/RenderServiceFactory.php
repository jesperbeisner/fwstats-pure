<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Service\Factory;

use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Interface\SessionInterface;
use Jesperbeisner\Fwstats\Service\RenderService;
use Jesperbeisner\Fwstats\Stdlib\Config;

class RenderServiceFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): RenderService
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        $viewsDir = $config->getString('views_dir');

        /** @var SessionInterface $session */
        $session = $container->get(SessionInterface::class);

        return new RenderService($viewsDir, $session);
    }
}
