<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ImageController;
use Jesperbeisner\Fwstats\Controller\ImageRenderController;
use Jesperbeisner\Fwstats\Stdlib\Config;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Request;

final class ImageRenderControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ImageRenderController
    {
        /** @var Config $config */
        $config = $container->get(Config::class);

        /** @var Request $request */
        $request = $container->get(Request::class);

        return new ImageRenderController(
            $config,
            $request,
        );
    }
}
