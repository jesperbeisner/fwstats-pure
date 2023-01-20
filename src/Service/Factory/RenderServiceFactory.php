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
        /** @var Config $config */
        $config = $container->get(Config::class);

        $viewsDirectory = $config->getString('views_directory');
        $appEnv = $config->getAppEnv();

        /** @var Request $request */
        $request = $container->get(Request::class);

        /** @var SessionInterface $session */
        $session = $container->get(SessionInterface::class);

        /** @var TranslatorInterface $translator */
        $translator = $container->get(TranslatorInterface::class);

        return new RenderService($viewsDirectory, $appEnv, $request, $session, $translator);
    }
}
