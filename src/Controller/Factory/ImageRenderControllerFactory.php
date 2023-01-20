<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ImageRenderController;
use Jesperbeisner\Fwstats\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Interface\FactoryInterface;
use Jesperbeisner\Fwstats\Stdlib\Config;

final readonly class ImageRenderControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ImageRenderController
    {
        return new ImageRenderController(
            $container->get(Config::class)->getRootDir(),
        );
    }
}
