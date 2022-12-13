<?php

declare(strict_types=1);

namespace Jesperbeisner\Fwstats\Controller\Factory;

use Jesperbeisner\Fwstats\Controller\ImageController;
use Jesperbeisner\Fwstats\Stdlib\Interface\ContainerInterface;
use Jesperbeisner\Fwstats\Stdlib\Interface\FactoryInterface;

final class ImageControllerFactory implements FactoryInterface
{
    public function build(ContainerInterface $container, string $serviceId): ImageController
    {
        return new ImageController();
    }
}
